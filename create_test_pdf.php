<?php
// Générer un PDF de test simple
$pdfContent = <<<'PDF'
%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj

2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj

3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>
endobj

4 0 obj
<< /Length 200 >>
stream
BT
/F1 24 Tf
50 700 Td
(Cours de Test - EduLearn LMS) Tj
/F1 14 Tf
0 -40 Td
(Chapitre 1 : Introduction au developpement Web) Tj
0 -30 Td
(Ce document est un exemple de lecon en format PDF.) Tj
0 -20 Td
(Il contient les concepts fondamentaux du cours.) Tj
0 -40 Td
(1. Les bases du HTML) Tj
0 -20 Td
(2. Le CSS pour le style) Tj
0 -20 Td
(3. JavaScript pour linteractivite) Tj
0 -40 Td
(Fin du chapitre 1.) Tj
ET
endstream
endobj

5 0 obj
<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>
endobj

xref
0 6
0000000000 65535 f
0000000009 00000 n
0000000058 00000 n
0000000115 00000 n
0000000266 00000 n
0000000518 00000 n

trailer
<< /Size 6 /Root 1 0 R >>
startxref
595
%%EOF
PDF;

// Sauvegarder dans storage/app/public/lecons/pdf/
$dir = __DIR__ . '/storage/app/public/lecons/pdf';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$filePath = $dir . '/lecon_test.pdf';
file_put_contents($filePath, $pdfContent);

echo "PDF de test cree : " . $filePath . "\n";
echo "Taille : " . filesize($filePath) . " octets\n";
