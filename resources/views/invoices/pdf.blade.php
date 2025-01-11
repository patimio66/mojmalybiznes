<!DOCTYPE html>
<html>

<head>
  <title>Faktura {{ $invoice->invoice_number }}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      font-size: 12px;
    }

    .container {
      padding: 24px;
      background-color: #ffffff;
      color: black;
    }

    .header,
    .buyer,
    .items {
      margin-bottom: 18px;
    }

    .header table,
    .buyer table {
      width: 100%;
      margin-bottom: 18px;
    }

    .header h2,
    .header p,
    .buyer h3,
    .buyer p {
      margin: 0;
    }

    .header h2 {
      font-size: 15px;
      font-weight: 600;
    }

    .header h3,
    .buyer h3 {
      font-size: 12px;
      font-weight: 700;
    }

    .header p,
    .buyer p {
      font-size: 12px;
    }

    .items table {
      width: 100%;
      border-collapse: collapse;
    }

    .items th,
    .items td {
      padding: 4px;
      border: 1px solid #e2e8f0;
    }

    .items th {
      background-color: #edf2f7;
      text-align: left;
    }

    .items td {
      text-align: right;
    }

    .items .title {
      text-align: left;
    }

    .items .total {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <table>
        <tr>
          <td>
            <h2>Faktura {{ $invoice->parent ? 'korygująca ' : '' }} nr: {{ $invoice->invoice_number }}</h2>
            @if ($invoice->parent)
              @php
                $currentInvoice = $invoice;
                while ($currentInvoice->parent) {
                    $currentInvoice = $currentInvoice->parent;
                }
                $lastParentInvoiceNumber = $currentInvoice->invoice_number;
              @endphp
              @if ($lastParentInvoiceNumber == $invoice->parent->invoice_number)
                <p>Numer ostatniej faktury korygującej: {{ $invoice->parent->invoice_number }}</p>
              @endif
              <p>Dotyczy faktury pierwotnej nr: {{ $lastParentInvoiceNumber }}</p>
              <p>Powód wystawienia: {{ $invoice->correction_reason }}</p>
            @endif
            <p>Data wystawienia: {{ $invoice->issue_date->format('d.m.Y') }}</p>
            <p>Data transakcji: {{ $invoice->transaction_date->format('d.m.Y') }}</p>
            <p>Termin płatności: {{ $invoice->due_date->format('d.m.Y') }}</p>
          </td>
          <td style="text-align: right;">
            <h3>Dane sprzedawcy:</h3>
            <p>{{ $invoice->seller_name }}</p>
            <p>{{ $invoice->seller_tax_id }}</p>
            <p>{{ $invoice->seller_address }}</p>
            <p>{{ $invoice->seller_postal_code }} {{ $invoice->seller_city }}</p>
            <p>{{ Str::upper($invoice->seller_country) }}</p>
            <p>{{ $invoice->seller_email }}</p>
            <p>{{ $invoice->seller_phone }}</p>
          </td>
        </tr>
      </table>
    </div>

    @if ($invoice->parent)
      <hr>
      <p style="font-weight:700; margin-bottom: 6px; margin-top: 6px; text-align:center">PRZED KOREKTĄ</p>
      <div class="buyer">
        <table>
          <tr>
            <td>
              <h3>Dane kupującego:</h3>
              <p>{{ $invoice->contractor_name }}</p>
              <p>{{ $invoice->contractor_tax_id }}</p>
              <p>{{ $invoice->contractor_address }}</p>
              <p>{{ $invoice->contractor_postal_code }} {{ $invoice->contractor_city }}</p>
              <p>{{ Str::upper($invoice->contractor_country) }}</p>
              <p>{{ $invoice->contractor_email }}</p>
              <p>{{ $invoice->contractor_phone }}</p>
            </td>
          </tr>
        </table>
      </div>
      <p style="font-weight:500; margin-bottom: 6px; font-size:15px">{{ $invoice->title }}</p>
      <div class="items">
        <table>
          <thead>
            <tr>
              <th class="title">Tytuł</th>
              <th>Ilość</th>
              <th>J.m.</th>
              <th>Cena jednostkowa</th>
              <th>Wartość</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($invoice->parent->items as $item)
              <tr>
                <td class="title">{{ $item->title }}</td>
                <td>{{ number_format($item->quantity, 2) }}</td>
                <td>{{ $item->uom }}</td>
                <td>{{ number_format($item->price, 2) }} zł</td>
                <td>{{ number_format($item->amount, 2) }} zł</td>
              </tr>
            @endforeach
            <tr class="total">
              <td colspan="4">Suma:</td>
              <td>{{ number_format($invoice->parent->amount, 2) }} zł</td>
            </tr>
            <tr class="total">
              <td colspan="4">Zapłacono:</td>
              <td>{{ $invoice->parent->is_paid ? number_format($invoice->parent->amount, 2) : '0.00' }} zł</td>
            </tr>
          </tbody>
        </table>
      </div>
    @endif

    @if ($invoice->parent)
      <hr>
      <p style="font-weight:700; margin-bottom: 6px; margin-top: 6px; text-align:center">PO KOREKCIE</p>
    @endif
    <div class="buyer">
      <table>
        <tr>
          <td>
            <h3>Dane kupującego:</h3>
            <p>{{ $invoice->contractor_name }}</p>
            <p>{{ $invoice->contractor_tax_id }}</p>
            <p>{{ $invoice->contractor_address }}</p>
            <p>{{ $invoice->contractor_postal_code }} {{ $invoice->contractor_city }}</p>
            <p>{{ Str::upper($invoice->contractor_country) }}</p>
            <p>{{ $invoice->contractor_email }}</p>
            <p>{{ $invoice->contractor_phone }}</p>
          </td>
        </tr>
      </table>
    </div>
    <p style="font-weight:500; margin-bottom: 6px; font-size:15px">{{ $invoice->title }}</p>
    <div class="items">
      <table>
        <thead>
          <tr>
            <th class="title">Tytuł</th>
            <th>Ilość</th>
            <th>J.m.</th>
            <th>Cena jednostkowa</th>
            <th>Wartość</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($invoice->items as $item)
            <tr>
              <td class="title">{{ $item->title }}</td>
              <td>{{ number_format($item->quantity, 2) }}</td>
              <td>{{ $item->uom }}</td>
              <td>{{ number_format($item->price, 2) }} zł</td>
              <td>{{ number_format($item->amount, 2) }} zł</td>
            </tr>
          @endforeach
          <tr class="total">
            <td colspan="4">Suma:</td>
            <td>{{ number_format($invoice->amount, 2) }} zł</td>
          </tr>
          <tr class="total">
            <td colspan="4">Zapłacono:</td>
            <td>{{ $invoice->is_paid ? number_format($invoice->amount, 2) : '0.00' }} zł</td>
          </tr>
        </tbody>
      </table>
    </div>

    @if ($invoice->tax_excemption_type == 'objective')
      <p>Sprzedawca zwolniony z VAT ze względu na nieprzekroczenie 200 000 PLN obrotu, tj. na podstawie art. 113 ust 1 i 9 ustawy o VAT.</p>
    @endif
    @if ($invoice->tax_excemption_type == 'subjective')
      <p>Sprzedawca zwolniony z VAT ze względu na rodzaj prowadzonej działalności, na podstawie art. 43 ust 1 ustawy o VAT.</p>
    @endif
    <table style="width: 100%;">
      <tr>
        <td style="text-align: center; height: 256px;">
          <p>_____________________________</p>
          <p>Podpis sprzedawcy</p>
        </td>
        <td style="text-align: center; height: 256px;">
          <p>_____________________________</p>
          <p>Podpis kupującego</p>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>
