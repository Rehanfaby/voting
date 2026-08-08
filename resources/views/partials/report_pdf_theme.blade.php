{{--
  Colorful pdfMake customize helper for ranking / qualified / eliminated exports.
  Expects: $pdfTheme ('qualified'|'eliminated'|'ranking'), $pdfTitle, $pdfSubtitle (optional)
--}}
@php
    $pdfBrand = $general_setting->site_title ?? ($general_setting->CompanyName ?? 'MULEMA GOSPEL');
@endphp
<script>
window.mgReportPdfTheme = {
    brand: @json($pdfBrand),
    title: @json($pdfTitle ?? 'Report'),
    subtitle: @json($pdfSubtitle ?? ''),
    theme: @json($pdfTheme ?? 'qualified'),
    generatedAt: @json(now()->format('d M Y H:i'))
};

window.mgCustomizeReportPdf = function (doc) {
    var cfg = window.mgReportPdfTheme || {};
    var theme = cfg.theme || 'qualified';
    var isElim = theme === 'eliminated';
    var isQual = theme === 'qualified';
    var headerColor = isElim ? '#b91c1c' : (theme === 'ranking' ? '#0a2350' : '#047857');
    var bannerSoft = isElim ? '#fee2e2' : (theme === 'ranking' ? '#dbeafe' : '#d1fae5');
    var altRow = isElim ? '#fef2f2' : (theme === 'ranking' ? '#eff6ff' : '#ecfdf5');
    var lightRow = isElim ? '#fff1f2' : (theme === 'ranking' ? '#f8fafc' : '#f0fdf4');
    var totalColColor = isElim ? '#b91c1c' : (theme === 'ranking' ? '#0a2350' : '#047857');
    var listLabel = isElim ? 'ELIMINATION LIST' : (isQual ? 'QUALIFIED LIST' : 'CONTESTANT RANKING');

    doc.pageMargins = [24, 36, 24, 36];
    doc.defaultStyle = doc.defaultStyle || {};
    doc.defaultStyle.fontSize = 9;
    doc.defaultStyle.color = '#111827';

    doc.styles = doc.styles || {};
    doc.styles.tableHeader = {
        fillColor: headerColor,
        color: '#ffffff',
        bold: true,
        fontSize: 9,
        alignment: 'left',
        margin: [3, 4, 3, 4]
    };
    doc.styles.tableBodyEven = { fillColor: altRow };
    doc.styles.tableBodyOdd = { fillColor: lightRow };

    // Strip DataTables default title / message blocks
    if (Array.isArray(doc.content)) {
        doc.content = doc.content.filter(function (node) {
            if (!node) return false;
            if (node.style === 'title' || node.style === 'message') return false;
            return true;
        });
    } else {
        doc.content = [];
    }

    // Branded header band + list-type badge
    doc.content.unshift(
        {
            table: {
                widths: ['*'],
                body: [[
                    {
                        stack: [
                            {
                                text: String(cfg.brand || 'MULEMA GOSPEL').toUpperCase(),
                                color: '#ffffff',
                                bold: true,
                                fontSize: 15,
                                alignment: 'center',
                                margin: [0, 2, 0, 4]
                            },
                            {
                                text: listLabel,
                                color: '#ffffff',
                                bold: true,
                                fontSize: 12,
                                alignment: 'center',
                                margin: [0, 0, 0, 2]
                            },
                            {
                                text: cfg.subtitle || cfg.title || '',
                                color: bannerSoft,
                                fontSize: 9,
                                alignment: 'center'
                            },
                            {
                                text: 'Generated ' + (cfg.generatedAt || ''),
                                color: '#ffffff',
                                fontSize: 8,
                                alignment: 'center',
                                margin: [0, 4, 0, 0]
                            }
                        ],
                        fillColor: headerColor,
                        margin: [10, 12, 10, 12]
                    }
                ]]
            },
            layout: 'noBorders',
            margin: [0, 0, 0, 14]
        }
    );

    // Find table node
    var tableNode = null;
    for (var c = 0; c < doc.content.length; c++) {
        if (doc.content[c] && doc.content[c].table && doc.content[c].table.body && doc.content[c].table.body.length > 1) {
            tableNode = doc.content[c];
            break;
        }
    }
    if (!tableNode || !tableNode.table || !tableNode.table.body) {
        return;
    }

    var body = tableNode.table.body;
    var colCount = body[0] ? body[0].length : 0;
    tableNode.table.widths = [];
    for (var w = 0; w < colCount; w++) {
        tableNode.table.widths.push('*');
    }
    tableNode.layout = {
        hLineWidth: function () { return 0.4; },
        vLineWidth: function () { return 0.3; },
        hLineColor: function () { return isElim ? '#fecaca' : (isQual ? '#a7f3d0' : '#cbd5e1'); },
        vLineColor: function () { return isElim ? '#fecaca' : (isQual ? '#a7f3d0' : '#e2e8f0'); },
        paddingLeft: function () { return 5; },
        paddingRight: function () { return 5; },
        paddingTop: function () { return 4; },
        paddingBottom: function () { return 4; },
        fillColor: function (rowIndex) {
            if (rowIndex === 0) return headerColor;
            return (rowIndex % 2 === 0) ? altRow : lightRow;
        }
    };

    function cellText(cell) {
        if (cell == null) return '';
        if (typeof cell === 'object') {
            if (cell.text != null) return String(cell.text);
            if (Array.isArray(cell.stack)) {
                return cell.stack.map(cellText).join(' ');
            }
            return '';
        }
        return String(cell);
    }

    function paintCell(cell, opts) {
        var o = opts || {};
        if (typeof cell !== 'object' || cell === null) {
            cell = { text: cell == null ? '' : String(cell) };
        }
        if (o.fillColor) cell.fillColor = o.fillColor;
        if (o.color) cell.color = o.color;
        if (o.bold) cell.bold = true;
        if (o.alignment) cell.alignment = o.alignment;
        if (o.fontSize) cell.fontSize = o.fontSize;
        return cell;
    }

    var headers = body[0] || [];
    var totalIdx = -1, posIdx = -1, statusIdx = -1, nameIdx = 0;
    for (var h = 0; h < headers.length; h++) {
        var label = cellText(headers[h]).toLowerCase();
        if (label.indexOf('total') !== -1) totalIdx = h;
        if (label.indexOf('position') !== -1) posIdx = h;
        if (label.indexOf('status') !== -1) statusIdx = h;
        if (label.indexOf('name') !== -1) nameIdx = h;
        headers[h] = paintCell(headers[h], {
            fillColor: headerColor,
            color: '#ffffff',
            bold: true,
            fontSize: 9
        });
    }

    for (var i = 1; i < body.length; i++) {
        var row = body[i];
        var statusText = statusIdx >= 0 ? cellText(row[statusIdx]).toLowerCase() : '';
        var rowElim = isElim || statusText.indexOf('elimin') !== -1;
        var rowQual = isQual || statusText.indexOf('qualif') !== -1;
        var fill = (i % 2 === 0) ? altRow : lightRow;

        if (theme === 'ranking') {
            fill = rowElim
                ? ((i % 2 === 0) ? '#fef2f2' : '#fff1f2')
                : ((i % 2 === 0) ? '#ecfdf5' : '#f0fdf4');
        }

        for (var j = 0; j < row.length; j++) {
            var opts = { fillColor: fill };
            if (j === totalIdx) {
                opts.bold = true;
                opts.color = rowElim ? '#b91c1c' : totalColColor;
            }
            if (j === posIdx) {
                opts.bold = true;
                opts.alignment = 'center';
                opts.color = rowElim ? '#b91c1c' : (rowQual ? '#047857' : headerColor);
            }
            if (j === statusIdx) {
                opts.bold = true;
                opts.alignment = 'center';
                opts.color = '#ffffff';
                opts.fillColor = rowElim ? '#dc2626' : '#059669';
            }
            if (j === nameIdx) {
                opts.bold = true;
            }
            row[j] = paintCell(row[j], opts);
        }
    }

    doc.footer = function (page, pages) {
        return {
            margin: [24, 0, 24, 0],
            columns: [
                {
                    text: String(cfg.brand || '') + '  ·  ' + listLabel,
                    color: headerColor,
                    fontSize: 8,
                    bold: true
                },
                {
                    text: page + ' / ' + pages,
                    alignment: 'right',
                    color: '#6b7280',
                    fontSize: 8
                }
            ]
        };
    };
};
</script>
