let currentPicture = null;
const userProfileIcon = document.querySelector('.user-profile-icon');
const settingsForm = document.querySelector('.security-form');

const fetchProfilePictures = async () => {

    const spinnerContainer = document.querySelector('.spinner-container');
    spinnerContainer.classList.add('spinner-visible');

    const response = await fetch(
        '/profilepictures',
        {
            method: 'GET'
        }
    );

    if (response.status !== 200) return;

    const profilePictures = await response.json();

    spinnerContainer.classList.remove('spinner-visible');
    spinnerContainer.classList.add('spinner-hidden');

    const container = document.querySelector('.pp-container');

    profilePictures.forEach((picture) => {
        const pictureImg = document.createElement('img');
        pictureImg.src = `data:image/png;base64,${picture.image}`;
        pictureImg.alt = `Image ${picture.id}`;
        pictureImg.style.cursor = 'pointer';

        pictureImg.addEventListener('click', () => { 
            currentPicture = picture.id;
            userProfileIcon.src = pictureImg.src;
        });
        container.appendChild(pictureImg);
    });
}

const submitChanges = async (e) => {
    e.preventDefault();

    const response = await fetch(
        '/user/update',
        {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            },
            body: JSON.stringify({ profilePicture: currentPicture })
        }
    );

    if (response.status !== 200) return;


}

settingsForm.addEventListener('submit', submitChanges);

fetchProfilePictures();