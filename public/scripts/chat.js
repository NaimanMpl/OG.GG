const socket = io('http://localhost:3000');

console.log(socket);

const sendMessage = (e) => {
    e.preventDefault();

    const input = document.querySelector('input');

    if (!input.value) {
        return;
    }

    socket.emit('message', input.value);
    console.log(socket)
    input.value = "";
    input.focus();

}

document.querySelector('form').addEventListener('submit', sendMessage);

socket.on('message', (data) => {
    const li = document.createElement('li');
    li.textContent = data;
    document.querySelector('ul').appendChild(li);
})