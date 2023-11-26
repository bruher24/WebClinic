export default class Server {
    async send(params = {}) {
        const query = Object.keys(params).map(key =>
            `${key}=${params[key]}`
        ).join("&");
        const response = await fetch(`http://feodal/api/?${query}`);
        const answer = await response.json();
        return answer?.result === 'ok' ? answer?.data : null;
    }

    async postSend(data = {}) {
        const response = await fetch(`http://localhost/api/?method=${data['method']}`, {
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
        data['method'] = 'signIn';
        return await this.send(data);
    }
}