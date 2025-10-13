
//Kelvin to celcius converter
function convertToDC(k) {
  return Math.trunc(k - 273.15);
}
//kelvin to fahrenheight converter
function convertToDF(k) {
  return Math.trunc((k - 273.15) * 9 / 5 + 32);
}
//au to km
function convertToKM(au) {
  return Math.trunc(au * 1.496e+8);
}
//convert to lunar distance
function convertToLD(au) {
  return Math.trunc(au * 389);
}
//convert mps to mph
function convertMPSToMPH(mps) {
  return mps * 2.237;
}


//grab troy weather data
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function () {
  if (this.readyState == 4 && this.status == 200) {
    var WD = JSON.parse(this.responseText);
    const weather = document.getElementById("weather");
    var elementsToAdd = ``;
    elementsToAdd += "<li class=\'miniHeading\'>Current Troy Weather</li>";
    elementsToAdd += "<img class=\'imgFormat\' src=\'images/" + WD.weather[0].main.toLowerCase() + ".png\' alt=\"weather-img\"/>";
    var elemBegin = "<li>";
    var elemEnd = "</li>";
    elementsToAdd += elemBegin + WD.weather[0].main + " - " + WD.weather[0].description + elemEnd;
    elementsToAdd += elemBegin + "&#127777;" + elemEnd;
    elementsToAdd += elemBegin + "Temperature: " + convertToDF(WD.main.temp) + " F / " + convertToDC(WD.main.temp) + " C" + elemEnd;
    elementsToAdd += elemBegin + "&#128167;" + elemEnd;
    elementsToAdd += elemBegin + "Humidity: " + WD.main.humidity + " %" + elemEnd;
    elementsToAdd += elemBegin + "&#128168;" + elemEnd;
    elementsToAdd += elemBegin + "Wind: " + convertMPSToMPH(WD.wind.speed) + " mph" + elemEnd;
    elementsToAdd += elemBegin + "&#9729;" + elemEnd;
    elementsToAdd += elemBegin + "Cloud Cover: " + WD.clouds.all + " %" + elemEnd;



    weather.innerHTML = elementsToAdd;

    const listItems = weather.querySelectorAll('li');

    listItems.forEach(item => {
      item.classList.add('listItem');
    });
  }
};
xhttp.open("GET", "https://corsproxy.io/?" + encodeURIComponent("https://api.openweathermap.org/data/2.5/weather?lat=42.73&lon=-73.68&appid=94b84de0952e602cf059e54614b14461"), true);
xhttp.send();

//grab Nasa Close approach data
var xhttp1 = new XMLHttpRequest();
xhttp1.onreadystatechange = function () {
  if (this.readyState == 4 && this.status == 200) {
    var CAD = JSON.parse(this.responseText);
    const cad = document.getElementById("cad");
    var star = "<img class='star-icon' src='images/cad.png' alt=\"star\"/>";
    var elementsToAdd = ``;
    elementsToAdd += "<li class=\'miniHeading\'>NASA Close Approach Forecast 2025</li>";
    var elemBegin = "<li>";
    var elemEnd = "</li>";
    var approaches = CAD.data;
    elementsToAdd += elemBegin + "~~~" + elemEnd;
    elementsToAdd += elemBegin + "Near Earth Objects' (NEOs) close approaches for 2025!" + elemEnd;

    //for each close approach:
    approaches.forEach(appr => {
      elementsToAdd += elemBegin + "~~~" + elemEnd;
      elementsToAdd += elemBegin + star + appr[0] + star + elemEnd;
      elementsToAdd += elemBegin + "&#9200; Time of Approach: " + appr[3] + elemEnd;
      elementsToAdd += elemBegin + "&#127775; Velocity (rel. to Earth): " + appr[7] + " km/s" + elemEnd;
      elementsToAdd += elemBegin + "&#128207; Distance from Earth: " + convertToKM(appr[4]) + " km or " + convertToLD(appr[4]) + " ld (lunar distance)" + elemEnd;

    })
    cad.innerHTML = elementsToAdd;

    const listItems = cad.querySelectorAll('li');

    listItems.forEach(item => {
      item.classList.add('listItem');
    });
  }
};
xhttp1.open("GET", "https://corsproxy.io/?" + encodeURIComponent("https://ssd-api.jpl.nasa.gov/cad.api?dist-max=5LD&date-min=2025-10-12&date-max=2025-12-31"), true);
xhttp1.send();



