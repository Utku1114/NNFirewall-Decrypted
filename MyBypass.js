var cloudscraper = require('cloudscraper');


var options = {
  method: 'GET',
  url:'https://exitus.me',
};
 
cloudscraper(options).then(console.log);
