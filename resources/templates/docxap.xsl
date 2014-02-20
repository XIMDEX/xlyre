<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
   <xsl:output method="html" encoding="utf-8"/>
    <xsl:include href="http://lab12.ximdex.net/ximdexxlyre/data/nodes/Picasso/templates/templates_include.xsl"/>
       <xsl:template name="docxap" match="docxap">
         <html xmlns="http://www.w3.org/1999/xhtml">

<!-- Note: Move this file into TEMPLATES folder [Ximdex Project] -->

         <head>
          <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8"/>
                <title>Xlyre</title>
         </head>
         <body uid="{@uid}">
          <div id="container">
            <xsl:apply-templates/>
                </div>
         </body>
       </html>
   </xsl:template>

  <xsl:template name="catalog" match="catalog">
              <h1 class="title">
                <xsl:value-of select="identifier"/>
              </h1>
            <div class="issued">
              <b>Issued: </b>
              <xsl:value-of select="issued"/>
            </div>
            <div class="modified">
              <b>Modified: </b>
              <xsl:value-of select="modified"/>
            </div>
    <div class="datasets">
        <h3>datasets:</h3>
        <ul>
            <xsl:for-each select="datasets/dataset">
                <li>
                  <div class="dataset">
                    <h3>(<xsl:value-of select="id"/>) [<xsl:value-of select="identifier"/>] <xsl:value-of select="language/title"/></h3>
                    <p>
                      <xsl:value-of select="language/description"/>
                    </p>
                    <ul>
                      <xsl:for-each select="formats">
                        <li>
                          <xsl:value-of select="format"/>
                        </li>
                      </xsl:for-each>
                    </ul>
                  </div>
                </li>
            </xsl:for-each>
        </ul>
    </div>
  </xsl:template>

  <xsl:template name="dataset" match="dataset">
    <h1 class="title">
      <xsl:value-of select="language/title"/>
    </h1>
    <h3 class="description">
      <xsl:value-of select="language/description"/>
    </h3>
    <div class="identifier">
      <b>Identifier: </b>
      <xsl:value-of select="identifier"/>
    </div>
    <div class="theme">
      <b>Theme: </b>
      <xsl:value-of select="theme"/>
    </div>
    <div class="periodicity">
      <b>Periodicity: </b>
      <xsl:value-of select="periodicity"/>
    </div>
    <div class="spatial">
      <b>Spatial: </b>
      <xsl:value-of select="spatial"/>
    </div>
    <div class="pubisher">
      <b>Publisher: </b>
      <xsl:value-of select="publisher"/>
    </div>
    <div class="issued">
      <b>Issued: </b>
      <xsl:value-of select="issued"/>
    </div>
    <div class="modified">
      <b>Modified: </b>
      <xsl:value-of select="modified"/>
    </div>
    <div class="reference">
      <b>Reference: </b> <xsl:value-of select="reference"/>
      </div>
    <div class="distributions">
        <h3>Distributions:</h3>
        <table border="0" cellpadding="10px" cellspacing="10px">
            <thead>
                <tr>
                  <th>Title</th>
                    <th>Identifier</th>
                    <th>Filename</th>
                    <th>Issued</th>
                    <th>Modified</th>
                    <th>MediaType</th>
                    <th>ByteSize</th>
                </tr>
            </thead>
            <xsl:for-each select="distributions/distribution">
                <tr>
                  <td>
                        <xsl:value-of select="language/title"/>
                    </td>
                    <td>
                      <xsl:value-of select="identifier"/>
                    </td>
                    <td>
                      <xsl:value-of select="filename"/>
                    </td>
                    <td>
                      <xsl:value-of select="issued"/>
                    </td>
                    <td>
                      <xsl:value-of select="modified"/>
                    </td>
                    <td>
                      <xsl:value-of select="mediatype"/>
                    </td>
                    <td>
                      <xsl:value-of select="bYtesize"/>
                    </td>
                </tr>
            </xsl:for-each>
        </table>
    </div>
 </xsl:template>

</xsl:stylesheet>
