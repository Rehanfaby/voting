{{--
  Colorful pdfMake customize helper for ranking / qualified / eliminated exports.
  Expects: $pdfTheme ('qualified'|'eliminated'|'ranking'), $pdfTitle, $pdfSubtitle (optional)
--}}
<script>
window.mgReportPdfTheme = {
    brand: @json($general_setting->site_title ?? 'MULEMA GOSPEL'),
    title: @json($pdfTitle ?? 'Report'),
    subtitle: @json($pdfSubtitle ?? ''),
    theme: @json($pdfTheme ?? 'qualified')
};

window.mgCustomizeReportPdf = function (doc) {
    var cfg = window.mgReportPdfTheme || {};
    var theme = cfg.theme || 'qualified';
    var headerColor = theme === 'eliminated' ? '#b91c1c' : (theme === 'ranking' ? '#0a2350' : '#047857');
    var altRow = theme === 'eliminated' ? '#fef2f2' : (theme === 'ranking' ? '#eff6ff' : '#ecfdf5');
    var accentRow = theme === 'eliminated' ? '#fee2e2' : (theme === 'ranking' ? '#dbeafe' : '#d1fae5');
    var totalColColor = theme === 'eliminated' ? '#b91c1c' : '#047857';

    doc.pageMargins = [28, 48, 28, 40];
    doc.defaultStyle = doc.defaultStyle || {};
    doc.defaultStyle.fontSize = 9;
    doc.defaultStyle.color = '#111827';

    doc.styles = doc.styles || {};
    doc.styles.tableHeader = {
        fillColor: headerColor,
        color: '#ffffff',
        bold: true,
        fontSize: 10,
        alignment: 'left',
        margin: [4, 4, 4, 4]
    };
    doc.styles.brand = { fontSize: 14, bold: true, color: headerColor };
    doc.styles.reportTitle = { fontSize: 12, bold: true, color: '#111827' };
    doc.styles.reportSub = { fontSize: 9, color: '#4b5563' };

    // Replace default title block with branded header
    if (doc.content && doc.content.length && doc.content[0].text) {
        doc.content.shift();
    }
    doc.content.unshift(
        { text: String(cfg.brand || '').toUpperCase(), style: 'brand', alignment: 'center', margin: [0, 0, 0, 2] },
        { text: cfg.title || '', style: 'reportTitle', alignment: 'center', margin: [0, 0, 0, 2] },
        {
            text: cfg.subtitle || '',
            style: 'reportSub',
            alignment: 'center',
            margin: [0, 0, 0, 12]
        }
    );

    // Find table node
    var tableNode = null;
    for (var c = 0; c < doc.content.length; c++) {
        if (doc.content[c].table) { tableNode = doc.content[c]; break; }
    }
    if (!tableNode || !tableNode.table || !tableNode.table.body) return;

    var body = tableNode.table.body;
    var colCount = body[0] ? body[0].length : 0;
    tableNode.table.widths = Array(colCount).fill('*');
    tableNode.layout = {
        hLineWidth: function () { return 0.4; },
        vLineWidth: function () { return 0.3; },
        hLineColor: function () { return '#cbd5e1';
        vLineColor: function () { return '#e2e8f0';
        paddingLeft: function () { return 6; },
        paddingRight: function () { return 6; },
        paddingTop: function () { return 5; },
        paddingBottom: function () { return 5; }
    };

    function cellText(cell) {
        if (cell == null) return '';
        if (typeof cell === 'object') return String(cell.text != null ? cell.text : '');
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
        return cell;
    }

    // Detect column indexes by header labels
    var headers = body[0] || [];
    var totalIdx = -1, posIdx = -1, statusIdx = -1, nameIdx = 0;
    for (var h = 0; h < headers.length; h++) {
        var label = cellText(headers[h]).toLowerCase();
        if (label.indexOf('total') !== -1) totalIdx = h;
        if (label.indexOf('position') !== -1) posIdx = h;
        if (label.indexOf('status') !== -1) statusIdx = h;
        if (label.indexOf('name') !== -1) nameIdx = h;
        headers[h] = paintCell(headers[h], { fillColor: headerColor, color: '#ffffff', bold: true });
    }

    for (var i = 1; i < body.length; i++) {
        var row = body[i];
        var statusText = statusIdx >= 0 ? cellText(row[statusIdx]).toLowerCase() : '';
        var isElim = theme === 'eliminated' || statusText.indexOf('elimin') !== -1;
        var isQual = theme === 'qualified' || statusText.indexOf('qualif') !== -1;
        var fill = (i % 2 === 0) ? altRow : '#ffffff';
        if (theme === 'ranking') {
            fill = isElim ? ((i % 2 === 0) ? '#fef2f2' : '#fff1f2') : ((i % 2 === 0) ? '#ecfdf5' : '#f0fdf4');
        } else if (isElim) {
            fill = (i % 2 === 0) ? '#fef2f2' : '#fff1f2';
        } else if (isQual) {
            fill = (i % 2 === 0) ? '#ecfdf5' : '#f0fdf4';
        }

        for (var j = 0; j < row.length; j++) {
            var opts = { fillColor: fill };
            if (j === totalIdx) {
                opts.bold = true;
                opts.color = isElim ? '#b91c1c' : totalColColor;
            }
            if (j === posIdx) {
                opts.bold = true;
                opts.alignment = 'center';
                opts.color = headerColor;
            }
            if (j === statusIdx) {
                opts.bold = true;
                opts.color = isElim ? '#b91c1c' : '#047857';
            }
            if (j === nameIdx) {
                opts.bold = true;
            }
            row[j] = paintCell(row[j], opts);
        }
    }

    doc.footer = function (page, pages) {
        return {
            columns: [
                { text: String(cfg.brand || ''), color: '#6b7280', fontSize: 8, margin: [28, 0, 0, 0] },
                { text: page + ' / ' + pages, alignment: 'right', color: '#6b7280', fontSize: 8, margin: [0, 0, 28, 0] }
            ]
        };
    };
};
</script>
