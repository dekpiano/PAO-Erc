<?php
function readDocx($filename) {
    if(!file_exists($filename)) {
        return "File not found.";
    }

    $zip = new ZipArchive;
    if ($zip->open($filename) === TRUE) {
        if (($index = $zip->locateName('word/document.xml')) !== false) {
            $data = $zip->getFromIndex($index);
            $zip->close();

            $xml = new DOMDocument();
            $xml->loadXML($data);
            
            $text = '';
            $paragraphs = $xml->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'p');
            
            foreach ($paragraphs as $p) {
                $texts = $p->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 't');
                $p_text = '';
                foreach ($texts as $t) {
                    $p_text .= $t->nodeValue;
                }
                $text .= $p_text . "\n";
            }
            return $text;
        }
        $zip->close();
    }
    return "Error reading zip or document.xml not found.";
}

$files = ["public/uploads/templates/leave_vacation_template.docx"];
$out = "";
foreach ($files as $f) {
    if(!file_exists($f)) {
        $out .= "$f not found\n";
    }
    $out .= "--- File: $f ---\n";
    $out .= readDocx($f);
    $out .= "\n\n";
}
file_put_contents('scratch/vacation_text.txt', $out);
?>
