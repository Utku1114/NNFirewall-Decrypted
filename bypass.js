var cloudscraper = require('cloudscraper');
 
cloudscraper.get('https://dstat.imagehub.host').then(console.log, console.error);
