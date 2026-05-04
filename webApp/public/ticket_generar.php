<?php

require '../vendor/autoload.php';
include "../resources/db/PedidoDB.php";
include "../resources/db/ItemPedidoDB.php";

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{

    //Page header
    public function Header()
    {
        // TCPDF sin librería GD estalla al procesar una imagen PNG con canal Alpha.
        // Omitimos incrustar el logo y en su lugar usamos un banner corporativo limpio en texto.

        // Set font
        $this->SetFont('helvetica', 'B', 18);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 15, 'NEXUS PASS COMPANY', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->Ln(6);
        $this->SetFont('helvetica', 'I', 10);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 15, 'Hardware y Membresías Digitales', 0, false, 'R', 0, '', 0, false, 'M', 'M');

        // Divider line
        $style = array('width' => 0.5, 'color' => array(200, 200, 200));
        $this->Line(15, 32, $this->getPageWidth() - 15, 32, $style);
    }

    // Page footer
    /*public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, 'Gracias por su preferencia. Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }*/
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('Nexus Pass');
$pdf->SetAuthor('Nexus Pass Company');
$pdf->SetTitle('Recibo de Compra - Nexus Pass');

// set margins
$pdf->SetMargins(15, 38, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

// ---------------------------------------------------------

$pdf->SetFont('helvetica', '', 11);
$pdf->AddPage();

$orderData = PedidoDB::getDatosPersonaOrdenPorIdOrden($_GET['idOrden']);

$dirString = '';
if (!empty($orderData['calle'])) {
    $dirString = '<br/>' . htmlspecialchars($orderData['calle']);
    if (!empty($orderData['numero'])) {
        $dirString .= ' #' . htmlspecialchars($orderData['numero']);
    }
}

$html = '
<h2 style="color:#222222; text-align:center; letter-spacing: 2px;">RECIBO DE COMPRA</h2>
<br><br>
<table cellpadding="5" cellspacing="0" style="width:100%; color:#444444; font-size:11px;">
    <tr>
        <td width="60%">
            <b>Enviado a:</b><br/>
            ' . htmlspecialchars($orderData['nombre'] . ' ' . $orderData['a_paterno']) . '<br/>
            ' . htmlspecialchars($orderData['correo_electronico']) . $dirString . '
        </td>
        <td width="40%" align="right">
            <b>Referencia de Orden:</b> #' . str_pad($orderData['id'], 6, '0', STR_PAD_LEFT) . '<br/>
            <b>Fecha de emisión:</b> ' . $orderData['fecha'] . '<br/>
            <b>Estatus:</b> Aprobado / Pagado
        </td>
    </tr>
</table>
<br><br><br>
<table cellpadding="8" style="width:100%; border-collapse:collapse; font-size:11px; color:#333;">
    <tr style="background-color:#f8f9fa; font-weight:bold; text-align:center;">
        <th width="45%" align="left" style="border-bottom: 2px solid #dddddd;">Descripción del Producto</th>
        <th width="20%" style="border-bottom: 2px solid #dddddd;">Precio Unitario</th>
        <th width="10%" style="border-bottom: 2px solid #dddddd;">Cant.</th>
        <th width="25%" align="right" style="border-bottom: 2px solid #dddddd;">Subtotal</th>
    </tr>
';

$items = ItemPedidoDB::getDatosItemsOrdenPorIdOrden($orderData['id']);

foreach ($items as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];
    $html .= '
    <tr>
        <td align="left" style="border-bottom: 1px solid #eeeeee;">' . htmlspecialchars($item["nombre"]) . '</td>
        <td align="center" style="border-bottom: 1px solid #eeeeee;">$' . number_format($item['precio'], 2) . '</td>
        <td align="center" style="border-bottom: 1px solid #eeeeee;">' . $item['cantidad'] . '</td>
        <td align="right" style="border-bottom: 1px solid #eeeeee;">$' . number_format($subtotal, 2) . '</td>
    </tr>';
}

$html .= '
    <tr>
        <td colspan="3" style="border:none;"></td>
        <td align="right" style="font-weight:bold; padding-top:15px; font-size:14px; border:none;">Total: $' . number_format($orderData['total'], 2) . '</td>
    </tr>
</table>
<br><br><br>
<!-- <div style="text-align:center; color:#888888; font-size:10px;">
    <p>Este documento es un comprobante de tu compra con Nexus Pass.<br/>Para dudas o aclaraciones comerciales, por favor contáctanos con tu número de orden a soporte@nexuspass.com.</p>
</div> --> 
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('Nexus_Recibo_' . $orderData['id'] . '.pdf', 'I');

