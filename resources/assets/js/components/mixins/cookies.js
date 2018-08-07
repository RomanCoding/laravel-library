export const cookies = {
    methods: {
        getCookie: function(cname, def = null) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');

            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) === 0) {
                    let result = c.substring(name.length, c.length);
                    if (result === 'false' || result === 'true') {
                        return result === 'true';
                    }
                    return result;
                }
            }
            return def;
        },
        setCookie: function(name, value) {
            document.cookie = name + '=' + value;
        }
    }
}