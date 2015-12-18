var fs = require('fs');
var args = require('system').args;
var page = require('webpage').create();


page.content = fs.read(args[1]);

page.viewportSize = {
    width: 1200,
    height: 1200
};

page.paperSize = {
    format: 'A4',
    orientation: 'portrait',
    margin: '1cm'
};

window.setTimeout(function() {
    page.render(args[1]);
    phantom.exit();
}, 8000);
