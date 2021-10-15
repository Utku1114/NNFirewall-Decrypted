var cloudscraper = require('cloudscraper');


var options = {
  method: 'GET',
  url:'https://hit.confighub.host',
};
 while(true)
 {
cloudscraper(options).then(console.log);
 }
