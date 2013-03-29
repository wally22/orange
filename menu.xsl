<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <!-- Настройка вывода -->
    <xsl:output method="html" encoding="utf-8" omit-xml-declaration="yes" doctype-system="about:legacy-compat"/>
    
    <!-- Основное преобразование -->
    <xsl:template match="/">
        <nav>
            <xsl:apply-templates select="/root/item"/>
        </nav>
    </xsl:template>

    <!-- Шаблон вывода меню -->
    <xsl:template match="/root/item">
        <a href="{@href}">
            <xsl:value-of select="@title"/>
        </a>
    </xsl:template>

</xsl:stylesheet>