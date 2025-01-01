function loadHTML(elementId, file) {
    fetch(file)
        .then(response => response.text())
        .then(data => {
            document.getElementById(elementId).innerHTML = data;
        });
}

let currentSection = 0;
const sections = [
    {
        image: "./assets/splash4.webp"
    },
    {
        image: "./assets/splash5.png" // Update with the correct image path
    },
    {
        image: "./assets/15.avif" // Update with the correct image path
    },
    {
        image: "./assets/msplash.png"
    },
    {
        image: "./assets/pspalsh.png"
    },
    {
        image: "./assets/dsplash.png"
    },
    {
        image: "./assets/csplash.png"
    },
];

function showSection(index) {
    // Update the image in the right column based on the current section
    document.getElementById('splashImage').src = sections[index].image;
    // Update the text in the left column
    const leftColumnText = document.querySelector('.content-section p');
}

function nextSection() {
    if (currentSection < sections.length - 1) {
        currentSection++;
    } else {
        currentSection = 0; // Loop back to the first section
    }
    showSection(currentSection);
}

function prevSection() {
    if (currentSection > 0) {
        currentSection--;
    } else {
        currentSection = sections.length - 1; // Loop back to the last section
    }
    showSection(currentSection);
}

window.onload = function() {
    // Show the first section on load
    showSection(currentSection);

    // Automatically move to the next section every 3 seconds
    setInterval(nextSection, 3000); // 3000 milliseconds = 3 seconds
};

window.onload = function() {
    loadHTML('header', 'header1.html');
    loadHTML('footer', 'footer.html');
    showSection(currentSection); // Show the first section on load

    // Automatically move to the next section every 2 seconds
    setInterval(nextSection, 3000); // 2000 milliseconds = 2 seconds
};
