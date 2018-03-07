import axios from 'axios';

const instance = axios.create({

    //baseURL: '/api/',

    //timeout: 1000,

    //headers: {'X-Custom-Header': 'foobar'}

});

export default {

    url: {

        all()
        {
            return instance.get( '/api/urls' )
                .then((rs) => rs.data)
                .catch(error => {
                    console.log(error.response)
                });
        },

        get(id)
        {
            return instance.get( '/api/urls/' + id )
                .then((rs) => rs.data)
                .catch(error => {
                    console.log(error.response)
                });
        },

        file(filename)
        {
            return instance.get('/' + filename, {responseType: 'blob'})
                .then((rs) => rs.data)
                .catch(error => {
                    console.log(error.response)
                });
        },

        add(url)
        {
            return instance.post('/api/urls', url)
                .then((rs) => rs.data)
                .catch(error => {
                    console.log(error.response)
                });
        },

    }

}