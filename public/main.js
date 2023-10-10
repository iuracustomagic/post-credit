function transliter( str ) {

    const ru = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
        'е': 'e', 'ё': 'e', 'ж': 'j', 'з': 'z', 'и': 'i',
        'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
        'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u',
        'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch', 'ш': 'sh',
        'щ': 'shch', 'ы': 'y', 'э': 'e', 'ю': 'u', 'я': 'ya', ' ': '-'
    }, n_str = [];

    str = str.replace(/[ъь]+/g, '').replace(/й/g, 'i');

    for ( var i = 0; i < str.length; ++i ) {
        n_str.push(
            ru[ str[i] ]
            || ru[ str[i].toLowerCase() ] == undefined && str[i]
            || ru[ str[i].toLowerCase() ].replace(/^(.)/, function ( match ) { return match.toUpperCase() })
        );
    }

    return n_str.join('');
}



const generateRandomString = (length, symbol) => {
               let result = '';
               const characters =
               'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
               const symbols = '()№;:?%*@#$&*()№;:?%*@#$&*';
               const charactersLength = characters.length;
               for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                }
               if(symbol) {
                   result += symbols.charAt(Math.floor(Math.random() * symbols.length));
               }

return result;
};


