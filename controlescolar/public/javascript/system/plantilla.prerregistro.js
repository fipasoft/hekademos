var Evt = {
	fire: function (element,event){
	    if (document.createEventObject){
	        // dispatch for IE
	        var evt = document.createEventObject();
	        return element.fireEvent('on'+event,evt);
	    }
	    else{
	        // dispatch for firefox + others
	        var evt = document.createEvent("HTMLEvents");
	        evt.initEvent(event, true, true ); // event type,bubbling,cancelable
	        return !element.dispatchEvent(evt);
	    }
	}
};

var Format = {
		
	int: function( n ){
		return Format.real(n, 0);
	},
	
	real: function( n, dec ){
		var d = ( dec >= 0 ? dec : 2 );
		var n = parseFloat( n.sub( ',', '' ) ).toFixed( d );
		if( isNaN( n ) ) return 'NaN';
		var tmp = n.split( '.' );
		var i = 1;
		var ints = tmp[ 0 ].split('').reverse();
		var decs = tmp[ 1 ];
		
		for( i = ints.length; i > 0 ; i-- ){
			if( i % 3 == 0 && i < ints.length ){
				ints.splice( i, 0, ',' );
			}
			
		}

		var m = ints.reverse().join('') + ( d > 0 ? '.' + decs.toString() : '' );
		return ( parseFloat( m ) == 0 ? '-' : m );
	}

};

var Serie = {
	sumar: function ( serie, prcs, includeAll ){
		var precision = ( prcs ? prcs : 2 ) ;
		return $$( serie ).inject( 0.00, function(acc, n) {
			acc = parseFloat( acc );
			var n = parseFloat( n.value || n.innerHTML.gsub( ',', '') );
			return parseFloat( acc + ( isNaN( n ) || ( includeAll && n.disabled ) ? 0 : n ) ).toFixed( precision ); 
		});
	}
};

function initCampos( e, eId ){
	// inicializar campos
	$$( ( eId ? '#' + eId + ' ' : '' ) + 'input.real, ' + ( eId ? '#' + eId + ' ' : '' ) + 'input._real_').each( function(campo){
		campo.onblur = function(){validar_cantidad(this);};
	});

	$$( ( eId ? '#' + eId + ' ' : '' ) + 'input.entero, ' + ( eId ? '#' + eId + ' '  : '' ) + 'input._entero_').each(function(campo){
		campo.onblur = function(){validar_entero(this);};
	});

	$$( 'input._fecha_').each( function(campo){
		campo.observe( 'blur', function( e ){
			var e = e.element();
			valFecha( e );
		});
//		campo.onblur = function(){ valFecha(campo) };
		var id = campo.identify();
		boton = $('btn_' + id );
		var ops = $H({electric : false, inputField : id, ifFormat : '%d/%m/%Y' });
		if( campo.hasClassName( '_ejercicio_' ) ){ ops.set( 'range', [ $F( 'EJE_ANNIO' ), $F( 'EJE_ANNIO' ) ]); }
		if( boton ){ boton.href = 'javascript:;'; ops.set( 'button', boton.id ); }
		Calendar.setup( ops.toObject() );
	});

	$$( ( eId ? '#' + eId + ' ': '') + '._lista_').each( function( lista ){
		var opcion = lista.options[ lista.selectedIndex ];
		lista.title = opcion.title;
		lista.onchange = function(){
			var opcion = this.options[ this.selectedIndex ];
			this.title = opcion.title;
		};
	});
};

Element.addMethods({

	identify: function( element ) {
	    element = $(element);
	    var id = Element.readAttribute(element, 'id');
	    if (id) return id;
	    do { id = 'anonymous_element_' + Element.idCounter++ } while ($(id));
	    element.id = id;
	    return id;
	}

});

Tablik = Class.create();

Tablik.prototype = {
	id: null,
	selectors:{
		addRow:     '.addRow',
		delRow:     '.delRow',
		nCopy:      '.nCopy',
		ico:        '.icon',
		init:       '.tablik',
		unique:     '.unique',
		sw:         '.switch',
		swAllRows:  '.swAllRows'
	},
	callbacks: {},
	options: {},
	tables: {},

	initialize: function( t ){
		this.id = $( t ).identify();
		this.register();
	},

	addRow: function( callbacks ){
		var before = ( callbacks && callbacks.before ? callbacks.before : this.callbacks.addRow_Before );
		var after = ( callbacks && callbacks.after ? callbacks.after : this.callbacks.addRow_After );
		
		var tr;
		
		if( before ){
			before.apply( this );
		}
		
		$$( '#' + this.id + ' table' ).each( function( t ){
			var rows = $( t ).rows;
			var n = $( t ).rows.length;
			if( n > 1 ){
				var i = 1;
				var nTr = null;
				
				// para excluir las filas que no se repiten
				while( ( n - i >= 1 ) ){
					if( !$(rows[ n - i ]).hasClassName( this.selectors.unique.sub( '.', '' ) ) ){
						nTr = rows[ n - i ];
						break;
					}
					
					i++;
				}
				
				// si hay algo que duplicar
				if( nTr ){
					
					// inserta
					var k = n + 1 - i;
					tr = $( t ).insertRow( k );
					// copia
					tr.innerHTML = nTr.innerHTML; // TODO: change replace for regexp
					if( tr.id.startsWith( 'anonymous_element_' ) ){
						tr.removeAttribute( 'id' );
					}else{
						var regexp = new RegExp( '_' + ( k - 1 ) + '$' );
						tr.id = tr.id.replace( regexp, "_" + k );
					}
					// inicializa
					this.initRow( tr.identify() );
				}
				
			}
		}.bind( this ) );
		
		if( after ){
			after.apply( tr, [ tr.identify() ] );
		}
	},

	delRow: function(){
		$$( '#' + this.id + ' table' ).each( function( t ){
			var rows = $( t ).rows;
			var n = $( t ).rows.length;
			if( n > 2 ){
				$( t ).deleteRow( n - 1 );
			}
		});
	},
	
	initRow: function( id, callbacks ){
		var before = ( callbacks && callbacks.before ? callbacks.before : this.callbacks.initRow_Before );
		var after = ( callbacks && callbacks.after ? callbacks.after : this.callbacks.initRow_After );
		var tr = $( id );
		
		if( before ){
			before.apply( tr, [ id ] );
		}
		
		initCampos( null, id );
		this.registerSws( id );
		this.actRow( null, id );
		this.resetRow( id );
		
		if( after ){
			after.apply( tr, [ id ] );
		}
		
	},

	register: function(){
		this.registerButtons();
		this.registerSws();
	},

	registerButtons: function(){
		$$( '#' + this.id + ' ' + this.selectors.addRow ).each( function( b ){
			if( b.tagName == 'A' ){
				b.href = 'javascript:;';
			}
			b.observe( 'click', function( event ){
				this.addRow();
			}.bind( this ));
		}.bind( this ));

		$$( '#' + this.id + ' ' + this.selectors.delRow ).each( function( b ){
			if( b.tagName == 'A' ){
				b.href = 'javascript:;';
			}
			b.observe( 'click', function( event ){
				this.delRow();
			}.bind( this ));
		}.bind( this ));

		$$( '#' + this.id + ' ' + this.selectors.swAllRows ).each( function( b ){
			if( b.tagName == 'A' ){
				b.href = 'javascript:;';
			}
			b.observe( 'click', function( event ){
				this.swAllRows( event );
			}.bind( this ));
		}.bind( this ));
	},

	registerSws: function( id ){
		var id =  id  ?  id  :  this.id;

		$$( '#' + id + ' ' + this.selectors.sw ).each( function( sw ){
			if( sw.tagName == 'A' ){
				sw.href = 'javascript:;';
			}
			if( sw.id.startsWith( 'anonymous_element_' ) ){
				sw.removeAttribute( 'id' );
			}
			sw.observe( 'click', function( event ){
				this.swRow( event );
			}.bind( this ) );
		}.bind( this ) );
	},

	// ROWS METHODS
	actAllRows: function( id ){
		$$('#' + id + ' input.' + this.selectors.sw ).each(function( sw ){
			if( sw.up( 'table' ).identify() == id ){
				sw.checked = true;
				sw.click();
			}
		}.bind( this ));
	},

	actRow: function( id, trId, callbacks ){
		var before = ( callbacks && callbacks.before ? callbacks.before : this.callbacks.actRow_Before );
		var after = ( callbacks && callbacks.after ? callbacks.after : this.callbacks.actRow_After );
		var tr = trId ? $( trId ) : $( id ).up( 'tr' );
		
		if( before ){
			before.apply( tr, [ tr.identify() ] );
		}
		
		tr.addClassName( 'selected' );
		$$('#' + tr.identify() + ' input, #' + tr.identify() + ' select').each(function( e ){
			e.enable();
			if( e.type == 'checkbox' && e.hasClassName( this.selectors.sw.sub( '.', '' ) ) ){
				e.checked = true;
			}
		}.bind( this ));
		
		if( after ){
			after.apply( tr, [ tr.identify() ] );
		}
		
		
	},

	dactAllRows: function( id ){
		$$('#' + id + ' input.' + this.selectors.sw ).each(function( sw ){
			if( sw.up( 'table' ).identify() == id ){
				sw.checked = false;
				sw.click();
			}
		}.bind( this ));
	},

	dactRow: function( id, trId, callbacks ){
		var tr = trId ? $( trId ) : $( id ).up( 'tr' );
		var before = ( callbacks && callbacks.before ? callbacks.before : this.callbacks.dactRow_Before );
		var after = ( callbacks && callbacks.after ? callbacks.after : this.callbacks.dactRow_After );
		
		if( before ){
			before.apply( tr, [ tr.identify() ] );
		}

		tr.removeClassName( 'selected' );
		
		$$('#' + tr.identify() + ' input, #' + tr.identify() + ' select').each( function( e ){
			if( !e.hasClassName('switch') && e.up( 'tr' ).identify() == tr.id  ){
				e.disable();
			}
			e.removeAttribute('_counted');
		});
		
		if( after ){
			after.apply( tr, [ tr.identify() ] );
		}
	},

	resetRow: function( id, callbacks ){
		var before = ( callbacks && callbacks.before ? callbacks.before : this.callbacks.resetRow_Before );
		var after = ( callbacks && callbacks.after ? callbacks.after : this.callbacks.resetRow_After );
		var tr = $( id );
		
		if( before ){
			before.apply( tr, [ id ] );
		}
		
		$$( '#' + id + ' *' ).each( function( e ){ 
			
			// reset id
			if( e.id.startsWith( 'anonymous_element_' ) ){
				e.removeAttribute( 'id' );
			}
			
			// form elements
			if( e.tagName == 'INPUT' || e.tagName == 'SELECT' ){
				if(e.type == 'text' || e.type == 'hidden'){
					e.clear();
				}else if(e.type == 'checkbox' && !e.hasClassName( this.selectors.sw.sub( '.', '' ) )){
					e.checked = false;
				}else if(e.type.startsWith('select') ){
					e.selectedIndex = 0;
					e.title = '';
				}
				e.removeAttribute('_counted');
			}
			
			// switches
			if( e.hasClassName( this.selectors.sw.sub( '.', '' ) )){
				e.show();
				e.enable();
			}
			
			// no copy
			if( e.hasClassName( this.selectors.nCopy.sub( '.', '' ) ) ){
				e.remove();
			}
			
			// ico
			if( e.hasClassName( this.selectors.ico.sub( '.', '' ) ) ){
				e.hide();
			}
		}.bind( this ) );
		
		if( after ){
			after.apply( tr, [ id ] );
		}

	},

	swAllRows: function( event ){
		e = event.element();

		var s = false;
		switch( e.tagName ){
			case 'A':
			case 'IMG':
					s = e.rel;
					e.rel = !e.rel;
				break;
			case 'INPUT':
					s = e.checked;
				break;
		}
		
		var id = e.up( 'table' ).identify();
		if( s ){
			this.actAllRows( id );
		}else{
			this.dactAllRows( id );
		}
	},

	swRow: function( event ){
		e = event.element();

		var s = false;
		switch( e.tagName ){
			case 'A':
					s = e.rel;
				break;
			case 'INPUT':
					s = e.checked;
				break;
		}

		var id = e.identify();
		if( s ){
			this.actRow( id );
		}else{
			this.dactRow( id );
		}

	}


};


Pre = Class.create();

Pre.prototype = {
	
	initialize: function( profesor_id ){
		this.profesor_id = profesor_id;
		
		this.inicializarFormulario();
		
		// tabla dinamica
		var t = $( 'tblCursos' );
		if( t ){
			t = new Tablik( t );
			t.callbacks.initRow_After = this.inicializarTablero;
			t.callbacks.dactRow_Before = this.desactivarFila;
			t.callbacks.addRow_After = this.inicializarFila;
		}
		this.inicializarTablero();
	},
	
	inicializarFormulario: function(){
		
		var a = $( 'aceptar' );
		if( a ){
			a.observe( 'click', function(){ 
				
				try{
					
					$$( '.alert' ).each( function( c ){
						throw 'Las horas asignadas superan el limite de la carga horaria.';
					});
					
					$( 'frm_prerreg' ).submit();
				
				}catch( e ){
					
					if( e != '_silent_' ){
						
						alert( e );
						
					}
				}
				
			});
		}
		
		var b = $( 'cancelar' );
		if( b ){
			b.observe( 'click', function(){ 
				
				document.location.href = '../../plantilla/profesores' ;
				
			});
		}
		
	},
	
	inicializarTablero: function( scope ){
		initCampos( scope );
		// evento para selectores de cursos		
		$$( ( scope ? '#' + scope + ' ' : '' ) + '.curso' ).invoke('observe', 'change', function( e ){
			var e = e.element();
			var curso_id = $F( e );
			var tr = e.up( 'tr' );
			var horas = tr.down( '.horas' );
			
			if( curso_id != '' ){
				// validacion de ids duplicados
				var  unico = 0;
				$$( '.curso' ).each( function( e ){
					if( $F( e ) == curso_id ){
						unico++;
					}
				});
				
				if( unico == 1 ){
					
					horas.update( e.options[ e.selectedIndex ].className );
					
				}else{
					
					Effect.Shake(e);
					e.selectedIndex = 0;
					horas.update( '-' );
					
				}
			}else{
				
				horas.update( '-' );
				
			}
			
			$( 'asignadas' ).update( Format.real( Serie.sumar( '.horas' ), 2 ) );
			
			// obtiene la diferencia de horas
			var req = $( 'requeridas' ).innerHTML.strip();
			var asg = $( 'asignadas' ).innerHTML.strip();
			req = ( req  == '-' ? 0 : req );
			asg = ( asg  == '-' ? 0 : asg );
			var diff = parseFloat( req ) - parseFloat( asg );
			diff = Format.real( diff + '', 2);
			$( 'disponibles' ).update( diff );
			
			// verifica el limite de la carga horaria
			if( parseFloat( diff.sub( ',', '' ) ) < 0 ){
				
				$( 'asignadas' ).up( 'td' ).addClassName( 'alert' );
				$( 'disponibles' ).up( 'td' ).addClassName( 'alert' );
				
			}else{
				
				$( 'asignadas' ).up( 'td' ).removeClassName( 'alert' );
				$( 'disponibles' ).up( 'td' ).removeClassName( 'alert' );
				
			}
			
		});
	},
	
	desactivarFila: function( id ){
		$$( '#' + id + ' .curso' ).each( function( e ){ 
			e.selectedIndex = 0;
			e.title = '';
			Evt.fire( e, 'change' );
		});
	},
	
	inicializarFila: function( id ){
		$$( '#' + id + ' .curso' ).each( function( e ){
			Evt.fire( e, 'change' );
		});
	}
	
};

function pre_init(){	
	pre = new Pre();
	
	$$( '.curso' ).each( function( e ){
		Evt.fire( e, 'change' );
	});
	
}

addDOMLoadEvent( pre_init );