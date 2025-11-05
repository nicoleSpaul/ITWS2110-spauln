// ---------- Utility Converters ----------
function convertToDC(k) {
    return Math.trunc(k - 273.15);
}

function convertToDF(k) {
    return Math.trunc((k - 273.15) * 9 / 5 + 32);
}

function convertToKM(au) {
    return Math.trunc(au * 1.496e8);
}

function convertToLD(au) {
    return Math.trunc(au * 389);
}

function convertMPSToMPH(mps) {
    return (mps * 2.237).toFixed(1);
}

// ---------- WEATHER DATA ----------
fetch("https://corsproxy.io/?" + encodeURIComponent("https://api.openweathermap.org/data/2.5/weather?lat=42.73&lon=-73.68&appid=94b84de0952e602cf059e54614b14461"))
    .then(response => {
        if (!response.ok) throw new Error("Weather API error");
        return response.json();
    })
    .then(WD => {
        const weather = document.getElementById("weather");
        let elementsToAdd = `
    <li class="miniHeading">Current Troy Weather</li>
    <img class="imgFormat" src="images/${WD.weather[0].main.toLowerCase()}.png" alt="weather-img" />
    <li>${WD.weather[0].main} - ${WD.weather[0].description}</li>
    <li>&#127777;</li>
    <li>Temperature: ${convertToDF(WD.main.temp)}°F / ${convertToDC(WD.main.temp)}°C</li>
    <li>&#128167;</li>
    <li>Humidity: ${WD.main.humidity}%</li>
    <li>&#128168;</li>
    <li>Wind: ${convertMPSToMPH(WD.wind.speed)} mph</li>
    <li>&#9729;</li>
    <li>Cloud Cover: ${WD.clouds.all}%</li>
    `;

        weather.innerHTML = elementsToAdd;

        weather.querySelectorAll("li").forEach(item => item.classList.add("listItem"));
    })
    .catch(err => console.error(err));


// ---------- NASA CLOSE APPROACH DATA ----------
fetch("https://corsproxy.io/?" + encodeURIComponent("https://ssd-api.jpl.nasa.gov/cad.api?dist-max=5LD&date-min=2025-10-12&date-max=2025-12-31"))
    .then(response => {
        if (!response.ok) throw new Error("NASA CAD API error");
        return response.json();
    })
    .then(CAD => {
        const cad = document.getElementById("cad");
        const star = "<img class='star-icon' src='images/cad.png' alt='star' />";

        let elementsToAdd = `
    <li class="miniHeading">NASA Close Approach Forecast 2025</li>
    <li>~~~</li>
    <li>Near Earth Objects' (NEOs) close approaches for 2025!</li>
    `;

        CAD.data.forEach(appr => {
            elementsToAdd += `
        <li>~~~</li>
        <li>${star} ${appr[0]} ${star}</li>
        <li>&#9200; Time of Approach: ${appr[3]}</li>
        <li>&#127775; Velocity (rel. to Earth): ${appr[7]} km/s</li>
        <li>&#128207; Distance from Earth: ${convertToKM(appr[4])} km or ${convertToLD(appr[4])} ld (lunar distance)</li>
      `;
        });

        cad.innerHTML = elementsToAdd;
        cad.querySelectorAll("li").forEach(item => item.classList.add("listItem"));
    })
    .catch(err => console.error(err));
