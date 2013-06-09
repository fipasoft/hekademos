<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:variable name="empty_string"/>
<xsl:template match="/">
<html>
  <xsl:choose>
  <xsl:when test="normalize-space(.) = $empty_string">
                  <br/>
                <p class="infoBox"><br/>No existen registros de calificaciones del alumno para el ciclo actual.</p>
                <br/>
                <br/>
    </xsl:when>
  <xsl:otherwise>
        <xsl:for-each select="calificaciones_reporte/tabla">
        <br/>
        <p class="tipBox">
        ESTAS CALIFICACIONES SON PARCIALES ACUMULATIVAS Y NO TIENEN VALIDEZ OFICIAL DE FORMA INDIVIDUAL.<br/>
        Para Cualquier aclaración, favor de acudir a la Dirección
        <br/>
        o a la Oficialía Mayor de esta Escuela.
        </p>
        <br/>
        <div>
         <table summary="Calificaciones del Alumno.">
            <thead>
            <tr><th>Materia</th><xsl:for-each select="periodos/periodo"><xsl:sort select="valor"/><th><xsl:value-of select="valor" /></th></xsl:for-each><th>Ordinario</th><th>Extraordinario</th><th>Estado</th></tr>
            </thead>
            <tbody>

            <xsl:for-each select="registro">
            <xsl:sort select="materia"/>
            <xsl:choose>
            <xsl:when test="position() mod 2">
            <tr class="odd" title="Profesor: {profesor}">
                <td><span class="sub"><xsl:value-of select="materia" /></span> </td>
                 <xsl:for-each select="calificacion">
                     <xsl:sort select="@periodo"/>

                     <xsl:choose>
                    <xsl:when test="valor &gt; 59">
                    <td><xsl:value-of select="valor" /></td>
                    </xsl:when>
                       <xsl:otherwise>

                       <xsl:choose>
                           <xsl:when test="valor = '-'">
                           <td>-</td>
                           </xsl:when>
                           <xsl:otherwise>
                               <td><span class="alerta" ><xsl:value-of select="valor" /></span></td>
                           </xsl:otherwise>
                    </xsl:choose>

                       </xsl:otherwise>
                    </xsl:choose>

                 </xsl:for-each>
                 <xsl:choose>
                    <xsl:when test="ordinario &gt; 59 or ordinario='A' ">
                    <td><xsl:value-of select="ordinario" /></td>
                    </xsl:when>
                       <xsl:otherwise>

                       <xsl:choose>
                           <xsl:when test="ordinario = '-'">
                           <td>-</td>
                           </xsl:when>
                           <xsl:otherwise>
                               <td><span class="alerta" ><xsl:value-of select="ordinario" /></span></td>
                           </xsl:otherwise>
                    </xsl:choose>

                       </xsl:otherwise>
                </xsl:choose>

                 <xsl:choose>
                    <xsl:when test="extra &gt; 59">
                    <td><xsl:value-of select="extra" /></td>
                    </xsl:when>
                       <xsl:otherwise>

                       <xsl:choose>
                           <xsl:when test="extra = '-'">
                           <td>-</td>
                           </xsl:when>
                           <xsl:otherwise>
                               <td><span class="alerta" ><xsl:value-of select="extra" /></span></td>
                           </xsl:otherwise>
                    </xsl:choose>

                       </xsl:otherwise>
                </xsl:choose>

                <xsl:choose>
                    <xsl:when test="status='Reprobado'">
                    <td><span class="alerta" ><xsl:value-of select="status" /></span></td>
                    </xsl:when>
                       <xsl:otherwise>
                       <td><xsl:value-of select="status" /></td>
                       </xsl:otherwise>
                </xsl:choose>

            </tr>

            </xsl:when>
            <xsl:otherwise>
            <tr class="no_odd" title="Profesor: {profesor}">
                <td><span class="sub"><xsl:value-of select="materia" /></span> </td>
                 <xsl:for-each select="calificacion">
                     <xsl:sort select="@periodo"/>
                     <xsl:choose>
                    <xsl:when test="valor &gt; 59">
                    <td><xsl:value-of select="valor" /></td>
                    </xsl:when>
                       <xsl:otherwise>

                       <xsl:choose>
                           <xsl:when test="valor = '-'">
                           <td>-</td>
                           </xsl:when>
                           <xsl:otherwise>
                               <td><span class="alerta" ><xsl:value-of select="valor" /></span></td>
                           </xsl:otherwise>
                    </xsl:choose>

                       </xsl:otherwise>
                    </xsl:choose>
                 </xsl:for-each>
                 <xsl:choose>
                    <xsl:when test="ordinario &gt; 59 or ordinario='A' " >
                    <td><xsl:value-of select="ordinario" /></td>
                    </xsl:when>
                       <xsl:otherwise>

                       <xsl:choose>
                           <xsl:when test="ordinario = '-'">
                           <td>-</td>
                           </xsl:when>
                           <xsl:otherwise>
                               <td><span class="alerta" ><xsl:value-of select="ordinario" /></span></td>
                           </xsl:otherwise>
                    </xsl:choose>

                       </xsl:otherwise>
                </xsl:choose>

                 <xsl:choose>
                    <xsl:when test="extra &gt; 59">
                    <td><xsl:value-of select="extra" /></td>
                    </xsl:when>
                       <xsl:otherwise>

                       <xsl:choose>
                           <xsl:when test="extra = '-'">
                           <td>-</td>
                           </xsl:when>
                           <xsl:otherwise>
                               <td><span class="alerta" ><xsl:value-of select="extra" /></span></td>
                           </xsl:otherwise>
                    </xsl:choose>

                       </xsl:otherwise>
                </xsl:choose>

                <xsl:choose>
                    <xsl:when test="status='Reprobado'">
                    <td><span class="alerta" ><xsl:value-of select="status" /></span></td>
                    </xsl:when>
                       <xsl:otherwise>
                       <td><xsl:value-of select="status" /></td>
                       </xsl:otherwise>
                </xsl:choose>


            </tr>
            </xsl:otherwise>
            </xsl:choose>

            </xsl:for-each>

            </tbody>
        </table>
        </div>
        </xsl:for-each>

    </xsl:otherwise>
    </xsl:choose>
    </html>
</xsl:template>

</xsl:stylesheet>