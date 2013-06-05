<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:variable name="empty_string"/>
<xsl:template match="/">
  <xsl:choose>
  <xsl:when test="normalize-space(.) = $empty_string">
    <br/>
    <p class="infoBox"><br/>No existen registros de asistencias del alumno para el ciclo actual.</p>
    <br/>
    <br/>
    </xsl:when>
  <xsl:otherwise>
        <br/>
        <br/>
        <p style="border-bottom:2px solid #EEEEEE;width:100%" ></p>

        <div>
        <h2 >Resumen</h2>
         <table summary="Resumen de asistencias del Alumno.">
        <thead>
        <tr>
        <th>Materia</th><th>Asistencias</th><th>Faltas</th><th>%</th><th>Estado</th>
        </tr>
        </thead>
        <tbody>
        <xsl:for-each select="asistencias_reporte/resumen/registro">
        <xsl:sort select="materia"/>
        <xsl:choose>
            <xsl:when test="position() mod 2">
                <tr class="odd">
                    <td class="column1">
                    <span class="sub">
                    <xsl:value-of select="materia" />
                    </span>
                    </td>
                    <td><xsl:value-of select="asistencias" /></td>
                    <td><xsl:value-of select="faltas" /></td>
                    <xsl:choose>
                    <xsl:when test="porcentaje &gt; 79">
                    <td><xsl:value-of select="porcentaje" /></td>
                    <td><xsl:value-of select="status" /></td>
                    </xsl:when>
                    <xsl:when test="porcentaje &gt; 59">
                    <td><span class="extraordinario" ><xsl:value-of select="porcentaje" /></span></td>
                    <td><span class="extraordinario" ><xsl:value-of select="status" /></span></td>
                    </xsl:when>
                       <xsl:otherwise>
                       <td><span class="alerta" ><xsl:value-of select="porcentaje" /></span></td>
                       <td><span class="alerta" ><xsl:value-of select="status" /></span></td>
                       </xsl:otherwise>
                    </xsl:choose>
                </tr>
            </xsl:when>
            <xsl:otherwise>
                <tr>
                    <td class="column1"><span class="sub">
                    <xsl:value-of select="materia" />
                    </span></td>
                    <td><xsl:value-of select="asistencias" /></td>
                    <td><xsl:value-of select="faltas" /></td>
                    <xsl:choose>
                    <xsl:when test="porcentaje &gt; 79">
                    <td><xsl:value-of select="porcentaje" /></td>
                    <td><xsl:value-of select="status" /></td>
                    </xsl:when>
                    <xsl:when test="porcentaje &gt; 59">
                    <td><span class="extraordinario" ><xsl:value-of select="porcentaje" /></span></td>
                    <td><span class="extraordinario" ><xsl:value-of select="status" /></span></td>
                    </xsl:when>
                       <xsl:otherwise>
                       <td><span class="alerta" ><xsl:value-of select="porcentaje" /></span></td>
                    <td><span class="alerta" ><xsl:value-of select="status" /></span></td>
                       </xsl:otherwise>
                    </xsl:choose>
                </tr>
             </xsl:otherwise>
        </xsl:choose>

        </xsl:for-each>
        </tbody>
        </table>
        </div>

        <br/>
        <br/>
        <p style="border-bottom:2px solid #EEEEEE;width:100%" ></p>
        <h2>Registro mensual</h2>
        <br/>
        <div style="text-align:center;" >
        <table style="width:400px;">
        <tbody>
        <tr class="odd">
        <td class="asistencia_1"  style="width:18px" ><span class="print">A</span></td>
        <td style="width:100px">Asistencia</td>
        <td class="asistencia_0" style="width:18px" ><span class="print">F</span></td>
        <td style="width:100px">Falta</td>
        <td class="asistencia_" style="width:18px" ></td><td style="width:100px">Sin clase</td></tr>
        </tbody>
        </table>
        </div>
        <br/>
        <xsl:for-each select="asistencias_reporte/tabla">
        <xsl:sort select="@mes_fecha" order="descending" />
        <div>
        <h2 class="sub"> <xsl:value-of select="@mes" /></h2>
         <table class="tabla_mes">
         <thead>
            <tr>
            <th>Materia</th><xsl:for-each select="dias/dia"><xsl:sort select="fecha" data-type="number" /><th><xsl:value-of select="fecha" /></th></xsl:for-each>
            </tr>
        </thead>
        <tbody>
            <xsl:for-each select="asistencias">
                <xsl:sort select="@materia"/>
            <xsl:choose>
            <xsl:when test="position() mod 2">
            <tr class="odd">
                <td><span class="sub"><xsl:value-of select="@materia" /></span></td>
                    <xsl:for-each select="asistencia"><xsl:sort select="dia_asistencia"/>
                        <td class="asistencia_{valor}">
                        <xsl:choose>
                        <xsl:when test="valor=0">
                        <span class="print">F</span>
                        </xsl:when>
                        <xsl:when test="valor=1">
                        <span class="print">A</span>
                        </xsl:when>
                          </xsl:choose>
                        </td>
                    </xsl:for-each>
            </tr>
            </xsl:when>
            <xsl:otherwise>
            <tr  class="no_odd">
                <td><span class="sub"><xsl:value-of select="@materia" /></span></td>
                    <xsl:for-each select="asistencia"><xsl:sort select="dia_asistencia"/>
                        <td class="asistencia_{valor}">
                        <xsl:choose>
                        <xsl:when test="valor=0">
                        <span class="print">F</span>
                        </xsl:when>
                        <xsl:when test="valor=1">
                        <span class="print">A</span>
                        </xsl:when>
                          </xsl:choose>
                        </td>
                    </xsl:for-each>
            </tr>
             </xsl:otherwise>
        </xsl:choose>
            </xsl:for-each>
        </tbody>
        </table>
        </div>
        <br/>
        </xsl:for-each>

    </xsl:otherwise>
    </xsl:choose>
</xsl:template>

</xsl:stylesheet>