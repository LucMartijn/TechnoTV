let Templates = ["slide-type-1", "slide-type-2", "slide-type-2", "slide-type-1"];
let slideNumber = 0;
let slideIdCounter = 0;
let frameLength = 0;
let Story = "";
let newsflashCreated = 1;


async function initializeSlideshow() {
    // Fetch news and wait for the response
    await getNews("Data/fetchNewsFlash.php");
    console.log(Story)
    for (const part of Story) {
        createNewsFlash(part); 
    }


    await getNews("Data/fetchGallery.php");
    console.log(Story)
    for (const part of Story) {
      createGallery(part); 
  }

  await getNews("Data/fetchStory.php");
  console.log(Story)
  for (const part of Story) {
    createStory(part); 

}
  await getNews("Data/fetchWeeklySchedule.php");
  console.log(Story)
  createWeeklySchedule(Story); 
}

// Fetch news from the provided path and store the response in `Story`
async function getNews(path) {
    const response = await fetch([path]);
    Story = await response.json();
    console.log(Story);
}

// Function to create slides based on the news data
function createNewsFlash(part) {


    // Get the template and container
    const Template = document.getElementById("index-slide-1");
    const container = document.getElementById('FlashManageList');
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


    // Append the cloned element to the container
    container.appendChild(clone);
}



function createGallery(part) {

  const Template = document.getElementById("index-slide-2");
  const container = document.getElementById('index-slideshow-frame');
  const clone = Template.content.cloneNode(true);
  const clonedElement = clone.querySelector('section');

  clone.getElementById("gallery-h1-1").textContent = part.title;

  // Set image source, title, and description
  clone.getElementById('gallery-image-1').src = part.image1_path;
  clone.getElementById('gallery-image-2').src = part.image2_path;
  clone.getElementById('gallery-image-3').src = part.image3_path;
  clone.getElementById('gallery-image-4').src = part.image4_path;
  clone.getElementById('gallery-image-5').src = part.image5_path;
  clone.getElementById('gallery-image-6').src = part.image6_path;
  clone.getElementById('gallery-image-7').src = part.image7_path;
  clone.getElementById('gallery-image-8').src = part.image8_path;
  clone.getElementById('gallery-image-9').src = part.image9_path;
  clone.getElementById('gallery-image-10').src = part.image10_path;

  // Prepare the slide class and id
  clonedElement.classList.add("Slide","Gallery");
  clonedElement.id = 'slideshowSlide-' + slideIdCounter;

  // Append the cloned element to the container
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
  const container = document.getElementById('weeklyManageList');
  const clone = Template.content.cloneNode(true);
  const clonedElement = clone.querySelector('section');

  for (let scheduleData of part) {
    const scheduleItem = document.createElement('li');
    scheduleItem.classList.add("Schedule-List-Item");
    scheduleItem.classList.add(`Schedule-${scheduleData.day}`);
    scheduleItem.textContent = `${scheduleData.day}  ${scheduleData.time} - ${scheduleData.title} : ${scheduleData.description}`;
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

    scheduleContainer.appendChild(scheduleItem);
    console.log(scheduleContainer);
  }



  // Prepare the slide id
  clonedElement.id = 'slideshowSlide-' + slideIdCounter;

  // Append the cloned element to the container
  container.appendChild(clone);
}


initializeSlideshow();



document.addEventListener('DOMContentLoaded', function() {
    // Function to handle edit button click
    function handleEditButtonClick(event) {
        const item = event.target.closest('li');
        const itemId = item.id;
        // Logic to edit the item
        console.log('Edit item with ID:', itemId);
        // Example: Open a modal or redirect to an edit page
    }

    // Function to handle delete button click
    function handleDeleteButtonClick(event) {
        const item = event.target.closest('li');
        const itemId = item.id;
        // Logic to delete the item
        console.log('Delete item with ID:', itemId);
        // Example: Remove the item from the DOM
        item.remove();
    }

    // Attach event listeners to all edit and delete buttons
    const editButtons = document.querySelectorAll('.edit-button');
    const deleteButtons = document.querySelectorAll('.delete-button');

    editButtons.forEach(button => {
        button.addEventListener('click', handleEditButtonClick);
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', handleDeleteButtonClick);
    });
});
