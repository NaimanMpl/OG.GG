const room = window.location.href.split("/").slice(-1)[0].toLowerCase();

let socket = null;


const fetchUser = async () => {
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
        return null;
    }

    return await response.json();
}

const sendMessage = async (e) => {
    e.preventDefault();

    const input = document.querySelector('input');

    if (!input.value) {
        return;
    }

    const user = await fetchUser();

    if (user === null || socket === null) return;

    socket.emit('message', { text: input.value, username: user.username });
    input.value = "";
    input.focus();

}

document.querySelector('form').addEventListener('submit', sendMessage);

const buildMemberCard = (username) => {
    const card = document.createElement('div');
    card.className = 'member-card';
    const memberName = document.createElement('span');
    memberName.textContent = username;
    card.appendChild(memberName)

    return card;
}

const handleConnections = (data) => {
    const li = document.createElement('li');
    const joinMessage = document.createElement('span');
    joinMessage.textContent = data.message;
    joinMessage.className = 'announce-message';
    li.appendChild(joinMessage);
    document.querySelector('.messages-history').appendChild(li);

    const memberContainer = document.querySelector('.chat-online-members--container');
    memberContainer.innerHTML = '';
    data.onlineUsers.forEach((username) => {
        const memberCard = buildMemberCard(username);
        memberContainer.appendChild(memberCard);
    });
}

const connect = async () => {

    const user = await fetchUser();

    socket = io('http://localhost:3000', {
        query: { 'room' : room, 'user' : user.username }
    });

    socket.on('message', (data) => {
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
    });

    socket.on('joinMessage', handleConnections);
    socket.on('leaveMessage', handleConnections);
}

connect();
