import axios from 'axios';

const instance = axios.create({

    baseURL: '/api/',

    //timeout: 1000,

    //headers: {'X-Custom-Header': 'foobar'}

});

export default {

    url: {

        all()
        {
            return instance.get( 'urls' )
                .then((rs) => rs.data)
                .catch(error => {
                    console.log(error.response)
                });
        },

        get(id)
        {
            return instance.get( 'urls/' + id )
                .then((rs) => rs.data)
                .catch(error => {
                    console.log(error.response)
                });
        },

        file(filename)
        {
            
        },

        add(url)
        {
            return instance.post('urls', url)
                .then((rs) => rs.data)
                .catch(error => {
                    console.log(error.response)
                });
        },

    }

}