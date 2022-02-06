var cloudscraper = require('cloudscraper');
 
cloudscraper.get('https://botflare.club').then(console.log, console.error);
