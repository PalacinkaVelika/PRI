<!DOCTYPE html>
<html lang="cs">

<?php $title = 'XML validátor' ?>

<head>
    <title><?= $title ?></title>
</head>

<body>
    <h1><?= $title ?></h1>

    <p>Nahrajte XML soubor, případně také XSD nebo DTD soubor.</p>
    <hr>
    <form enctype="multipart/form-data" method="POST">
        <table>
            <tr>
                <td>XML soubor:</td>
                <td>
                    <!-- Allow users to choose from XML files on disk -->
                    <select name="xml">
                        <?php
                        foreach (glob("xml/*.xml") as $file) {
                            echo "<option value=\"$file\">$file</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>XSD/DTD soubor:</td>
                <td><input type="file" name="schema" data-max-file-size="2M"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" type="Odeslat"></td>
            </tr>
        </table>
    </form>
    <hr>

    <?php
    function printErrors()
    { ?>
        <table>
            <?php foreach (libxml_get_errors() as $error) { ?>
                <tr>
                    <td><?= $error->line ?></td>
                    <td><?= $error->message ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php }

    function validate($xmlPath, $schemaPath = '')
    {
        $doc = new DOMDocument;

        libxml_use_internal_errors(true);
        $doc->loadXML(file_get_contents($xmlPath));
        printErrors();
        libxml_use_internal_errors(false);

        // Do we have a root and a schema?
        @$root = $doc->firstElementChild->tagName;
        if ($root && $schemaPath) {
            echo "<p>Validating using XSD. Root: <b>$root</b></p>";

            $xsd = new DOMDocument;
            $xsd->load($schemaPath);

            libxml_use_internal_errors(true);
            $isValid = $doc->schemaValidate($xsd);
            printErrors();
            libxml_use_internal_errors(false);

            return $isValid;
        }

        // Fallback to DTD validation
        echo "<p>Validating using DTD. Root: <b>$root</b></p>";

        // ... (rest of the existing DTD validation logic)

        return $isValid;
    }

    $xmlFile = @$_FILES['xml'];
    $schemaFile = @$_FILES['schema'];

    if (@$xmlTmpName = $xmlFile['tmp_name']) {
        $schemaTmpName = $schemaFile['tmp_name'];
        $isValid = validate($xmlTmpName, $schemaTmpName);
        if ($isValid)
            echo "The uploaded XML file is valid.";
    }
    ?>


    <?php
        function handleFormSubmission() {
            $selectedXml = $_POST['xml'];
            $schemaFile = @$_FILES['schema'];
            echo $selectedXml;
            
            echo "<a href='".$selectedXml.".'>Studentů Catalog</a>";
            // XML
            $xml = new DOMDocument;
            $xml->load($selectedXml);
            // XSL
            $xsl_special = substr($selectedXml, 0, -4);
            $xsl = new DOMDocument;
            $xsl->load($xsl_special.'.xsl');
            // transformer
            $xslt = new XSLTProcessor();
            $xslt->importStylesheet($xsl);
            $transformovany_xml = $xslt->transformToXml($xml);
            // output
            echo $transformovany_xml;

        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handleFormSubmission();
        }
    ?>

</body>

</html>
