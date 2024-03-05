<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <!-- Match the root element of the XML -->
  <xsl:template match="/">

    <!-- Start HTML document -->
    <html>
      <body>

        <!-- Heading -->
        <h2>Krásní studenti</h2>

        <!-- Table -->
        <table border="1">
          <tr bgcolor="#9acd32">
            <th style="text-align:left">Jméno</th>
            <th style="text-align:left">Přijméní</th>
          </tr>

          <!-- Loop through each 'student' element -->
          <xsl:for-each select="studenti/student">
            <tr>
              <td>
                <!-- Extract and display 'Fname' -->
                <xsl:value-of select="note/Fname"/>
              </td>
              <td>
                <!-- Extract and display 'Lname' -->
                <xsl:value-of select="note/Lname"/>
              </td>
            </tr>
          </xsl:for-each>
        </table>

      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>