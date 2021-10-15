var cloudscraper = require('cloudscraper');


var options = {
  method: 'GET',
  url:'https://dev.exitus.me/',
};
 
cloudscraper(options).then(console.log);
