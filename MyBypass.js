var cloudscraper = require('cloudscraper');
 
cloudscraper.get('https://hit.confighub.host').then(console.log, console.error);
