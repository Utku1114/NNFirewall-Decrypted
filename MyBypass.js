var cloudscraper = require('cloudscraper');


var options = {
  method: 'GET',
  url:'https://famy.cc',
};
 
cloudscraper(options).then(console.log);
