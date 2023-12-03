const room = window.location.href.split("/").slice(-1)[0].toLowerCase();
const socket = io('http://localhost:3000', {
    query: { 'room' : room }
});

const connect = async () => {
    const response = await fetch(
        '/user/me',
        {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        }
    );

    if (response.status !== 200) {
        return;
    }

    const userData = await response.json();
    socket.emit('connect', userData.username);

}


const sendMessage = async (e) => {
    e.preventDefault();

    const input = document.querySelector('input');

    if (!input.value) {
        return;
    }

    const response = await fetch(
        '/user/me',
        {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        }
    );

    if (response.status !== 200) {
        return;
    }

    const userData = await response.json();

    socket.emit('message', { text: input.value, username: userData.username });
    input.value = "";
    input.focus();

}

document.querySelector('form').addEventListener('submit', sendMessage);

socket.on('message', (data) => {
    console.log(data);
    const li = document.createElement('li');
    const messageTime = document.createElement('span');
    messageTime.className = 'message-time';
    messageTime.textContent = data.time;
    const userTitle = document.createElement('span');
    userTitle.className = 'message-title';
    userTitle.textContent = `${data.login}: `;
    const userMessage = document.createElement('span');
    userMessage.className = 'message-content';
    userMessage.textContent = `${data.text}`

    li.appendChild(messageTime);
    li.appendChild(userTitle);
    li.appendChild(userMessage);

    document.querySelector('.messages-history').appendChild(li);
})