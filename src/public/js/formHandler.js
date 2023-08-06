class Server {
    async send(params = {}) {
        const query = Object.keys(params).map(key =>
            `${key}=${params[key]}`
        ).join("&");
        const response = await fetch(`/api/index.php/?${query}`);
        const answer = await response.json();
        return answer?.result === 'ok' ? answer?.data : null;
    }

    async postSend(data = {}) {
        const query = Object.keys(data).map(key =>
            `${key}=${data[key]}`
        ).join("&");
        const response = await fetch(`http://localhost/?${query}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        const answer = await response.json();
        return answer?.result === 'ok' ? answer?.data : null;
    }

    async signIn(data) {
        data['method'] = 'signUp';
        return await this.postSend(data);
    }
}

function selectOnChange() {
    let select= document.getElementById('roleSelect');
    select.addEventListener('change', function () {
        if(select.value === 'doctor') {
            document.getElementById('specLabel').style.display = 'block';
        }else {
            document.getElementById('specLabel').style.display = 'none';
        }
    });
}

window.onload = e => {
    selectOnChange();
    const form = document.querySelector('#signIn');
    form.addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(form);
        const server = new Server();
        let params = new Map;
        for(let pair of data.entries()) {
            params.set(pair[0], pair[1]);
        }
        return server.signIn(params);

    });
}