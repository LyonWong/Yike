function renderTOC(toc) {
    $('#toc').html(
        parseList(toc, 0)
    );
}

function renderDoc(path) {
    window.md = window.md || window.markdownit()
        .use(window.markdownitContainer)
        .use(window.markdownitFootnote)
        .use(window.markdownitIns)
        .use(window.markdownitMark)
        .use(window.markdownitSup);
    $.get(path + '.md?'+window.ts, function (doc) {
        $('#doc').html(
            window.md.render(doc)
        )
    })
}

function parseList(conf, tier) {
    var html = '';
    html += "<ul class='tier tier-" + tier + "'>";
    $.each(conf, function (i, item) {
        if (item.path) {
            html += "<li class='link' data-path='" + item.path + "'><a href='javascript:void(0);'>";
        } else if (item.link) {
            html += "<li class='link'><a href='" + item.link + "'>";
        } else {
            html += "<li><a>";
        }
        html += item.name + "</a></li>";
        if (item.next) {
            html += parseList(item.next, tier + 1)
        }
    });
    html += "</ul>";
    return html;
}
