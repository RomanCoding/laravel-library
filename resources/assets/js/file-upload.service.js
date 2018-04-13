import * as axios from 'axios';

function upload(formData) {
    const url = '/files';
    return axios.post(url, formData)
        .then(x => x.data)
        .catch((error) => {
            return Promise.reject(error);
        });
}

export {upload}