let el = document.getElementById('index-slideshow-frame');
let Templates = ["slide-type-1", "slide-type-2", "slide-type-2", "slide-type-1"];
let slideNumber = 0;
let slideIdCounter = 0;
let frameLength = 0;
let Story = "";
let newsflashCreated = 1;




let wakeLock = null;

// Request a screen wake lock
async function requestWakeLock() {
    try {
        wakeLock = await navigator.wakeLock.request('screen');
            
    } catch (err) {
        console.error(`${err.name}, ${err.message}`);
    }
}

window.addEventListener('focus', requestWakeLock);

window.addEventListener('blur', () => {
    if (wakeLock !== null) {
        wakeLock.release();
        wakeLock = null;
    }
});
// Async function to handle the full flow
async function initializeSlideshow() {
    // Fetch news and wait for the response
    await getNews("Data/fetchNewsFlash.php");
    for (const part of Story) {
        createNewsFlash(part); 
        slideIdCounter++;
    }


    await getNews("Data/fetchGallery.php");
    for (const part of Story) {
      createGallery(part); 
      slideIdCounter++;
    }

    await getNews("Data/fetchStory.php");
    for (const part of Story) {
      createStory(part); 
      slideIdCounter++;

    }
    await getNews("Data/fetchWeeklySchedule.php");
    createWeeklySchedule(Story); 
    slideIdCounter++;

    let frame = document.getElementById("index-slideshow-frame");
    frameLength = frame.childElementCount; // Starts at 0
    slideNumber = Math.floor(Math.random() * frameLength);
    slideshow();
}

// Fetch news from the provided path and store the response in `Story`
async function getNews(path) {
    const response = await fetch([path]);
    Story = await response.json();
    console.log(Story);
}

// Function to create slides based on the news data
function createNewsFlash(part) {
    // Select a random slide template from the Templates array
    let randomSlide = Templates[Math.floor(Math.random() * Templates.length)];

    // Get the template and container
    const Template = document.getElementById("index-slide-1");
    const container = document.getElementById('index-slideshow-frame');
    const clone = Template.content.cloneNode(true);
    const clonedElement = clone.querySelector('section');

    // Set image source, title, and description
    clone.querySelector('img').src = part.flashimage1;
    if (part.flashimage2 === null) {
      clone.getElementById('template-image-2').style.display = "none";
    }else {
      clone.getElementById('template-image-2').src = part.flashimage2;
    }
    clone.querySelector('h1').textContent = part.title;
    clone.querySelector('article').textContent = part.flashdesc1;

    // Prepare the slide class and id
    clonedElement.classList.add("Slide", randomSlide);
    clonedElement.id = 'slideshowSlide-' + slideIdCounter;

    // Append the cloned element to the container
    container.appendChild(clone);
}



function createGallery(part) {
  // Get the template and container
  const Template = document.getElementById("index-slide-2");
  const container = document.getElementById('index-slideshow-frame');
  const clone = Template.content.cloneNode(true);
  const clonedElement = clone.querySelector('section');

  clone.getElementById("gallery-h1-1").textContent = part.title;

  // Array of image paths
  const imagePaths = [
      part.image1_path, part.image2_path, part.image3_path, part.image4_path, part.image5_path,
      part.image6_path, part.image7_path, part.image8_path, part.image9_path, part.image10_path
  ];

  // Iterate over each image element and set the source and rotation if the path is not null
  imagePaths.forEach((path, count) => {
      const imageElement = clone.getElementById(`gallery-image-${count + 1}`);
      if (path) {
          imageElement.src = path;
          const rotation = Math.random() * 10 - 5;
          imageElement.style.transform = `rotate(${rotation}deg)`;
      } else {
          imageElement.style.display = 'none'; // Hide the image if the path is null
      }
  });

  clonedElement.classList.add("Slide", "Gallery");
  clonedElement.id = 'slideshowSlide-' + slideIdCounter;

  container.appendChild(clone);
}



function createStory(part) {
  // Get the template and container
  const Template = document.getElementById("index-slide-3");
  const container = document.getElementById('index-slideshow-frame');
  const clone = Template.content.cloneNode(true);
  const clonedElement = clone.querySelector('section');

  clone.querySelector('h1').textContent = part.title;
  clone.getElementById("Story-article-1").textContent = part.storydesc1;
  clone.getElementById("Story-article-2").textContent = part.storydesc2;

  // Prepare the slide class and id
  clonedElement.classList.add("Slide");
  clonedElement.id = 'slideshowSlide-' + slideIdCounter;

  // Append the cloned element to the container
  container.appendChild(clone);
}

function createWeeklySchedule(part) {
  const Template = document.getElementById("index-slide-4");
  const container = document.getElementById('index-slideshow-frame');
  const clone = Template.content.cloneNode(true);
  const clonedElement = clone.querySelector('section');

  // Track if any schedule items are added
  let hasData = false;

  for (let scheduleData of part) {
    const scheduleItem = document.createElement('li');
    scheduleItem.classList.add("Schedule-List-Item");
    scheduleItem.classList.add(`Schedule-${scheduleData.day}`);
    scheduleItem.textContent = `${scheduleData.day}  ${scheduleData.time} - ${scheduleData.title} : ${scheduleData.description}`;

    let scheduleContainer = null;
    switch (scheduleData.day) {
      case 'Maandag':
        scheduleContainer = clone.getElementById('monday-schedule');
        break;
      case 'Dinsdag':
        scheduleContainer = clone.getElementById('tuesday-schedule');
        break;
      case 'Woensdag':
        scheduleContainer = clone.getElementById('wednesday-schedule');
        break;
      case 'Donderdag':
        scheduleContainer = clone.getElementById('thursday-schedule');
        break;
      case 'Vrijdag':
        scheduleContainer = clone.getElementById('friday-schedule');
        break;
      default:
        break;
    }

    if (scheduleContainer) {
      console.log(scheduleContainer);
      scheduleContainer.appendChild(scheduleItem);
      hasData = true; // Mark that we have added at least one item
    }
  }

  // Only append the cloned element if it has data
  if (hasData) {
    // Prepare the slide id
    clonedElement.id = 'slideshowSlide-' + slideIdCounter;
    // console.log(hasData);
    // Append the cloned element to the container
    container.appendChild(clone);
  }
}





// Slideshow function
let previousSlide = -1; // Initialize with a value that doesn't match any valid slide index

function slideshow() {
    // Show the current slide
    document.getElementById("slideshowSlide-" + slideNumber).style.display = "grid";
    
    setTimeout(() => {
        console.log("slideshowSlide-" + slideNumber);
        document.getElementById("slideshowSlide-" + slideNumber).style.opacity = "1"; // Transition in
    }, 100);
    
    setTimeout(() => {
        document.getElementById("slideshowSlide-" + slideNumber).style.opacity = "0"; // Transition out
    }, 14250);//sets the opacity to 0 for the transition, set about 750 miliseconds before the item hides
    
    setTimeout(() => {
        document.getElementById("slideshowSlide-" + slideNumber).style.display = "none"; // Hide the item
        
        // Pick a new random slide number that's different from the previous one
        do {
            slideNumber = Math.floor(Math.random() * frameLength);
            console.log(frameLength)
        } while (slideNumber === previousSlide);

        // Store the current slide as the previous one
        previousSlide = slideNumber;

    }, 15000);//time untill the item gets hidden, 750 miliseconds after the opacity 0 for a smooth transition

    setTimeout(slideshow, 15000);//Delay for every slide after
}



// Run the function that goes through every other function
initializeSlideshow();











// Extra feature: Add some funny things with battery (↓ check link and functions below ↓)
// https://whatwebcando.today/battery-status.html

//Battery info, not using but might be fun to add?
/*navigator.getBattery().then((battery) => {
    function updateAllBatteryInfo() {
      updateChargeInfo();
      updateLevelInfo();
      updateChargingInfo();
      updateDischargingInfo();
    }
    updateAllBatteryInfo();
  
    battery.addEventListener("chargingchange", () => {
      updateChargeInfo();
    });
    function updateChargeInfo() {
      console.log(Battery charging? ${battery.charging ? "Yes" : "No"});
    }
  
    battery.addEventListener("levelchange", () => {
      updateLevelInfo();
    });
    function updateLevelInfo() {
      console.log(Battery level: ${battery.level * 100}%);
    }
  
    battery.addEventListener("chargingtimechange", () => {
      updateChargingInfo();
    });
    function updateChargingInfo() {
      console.log(Battery charging time: ${battery.chargingTime} seconds);
    }
  
    battery.addEventListener("dischargingtimechange", () => {
      updateDischargingInfo();
    });
    function updateDischargingInfo() {
      console.log(Battery discharging time: ${battery.dischargingTime} seconds);
    }
  });

*/