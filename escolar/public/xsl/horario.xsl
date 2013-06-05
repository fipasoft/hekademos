<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:variable name="empty_string"/>
<xsl:template match="/">
<table>
<tr>
<xsl:for-each select="horario/dias/dia">
    <td><xsl:value-of select="valor" /></td>
</xsl:for-each>
</tr>
<xsl:for-each select="horario/registro">
<xsl:sort select="curso/nombre"/>
<tr>
    <td><xsl:value-of select="curso/nombre" /></td>
</tr>
</xsl:for-each>
</table>
</xsl:template>
</xsl:stylesheet>