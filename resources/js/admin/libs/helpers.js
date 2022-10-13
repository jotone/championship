export const Helpers = {
  /**
   * Array diff
   *
   * @param {Array} arr1
   * @param {Array} arr2
   * @return {Array}
   */
  arrayDiff: (arr1, arr2) => arr1.filter(x => !arr2.includes(x)),
  /**
   * Set id to remove link
   *
   * @param {string} link
   * @param {string|int} id
   * @param {int} position
   * @returns {string}
   */
  buildUrl: (link, id, position = 1) => {
    let resultLink = link.split('/');
    resultLink[resultLink.length - position] = id;
    resultLink = resultLink.join('/');
    return resultLink;
  },
  /**
   * Parse url with it parameters
   *
   * @param url
   * @returns {{path: string, search: ({}|{}), hash: (string|null)}}
   */
  parseUrl: (url = null) => {
    const row = null === url ? window.location.href : url

    let [path, temp] = row.split('?')

    let params, hash;
    if (typeof temp !== 'undefined') {
      [params, hash] = temp.split('#')
      params = params.split('&').reduce((sum, curr) => {
        const temp = curr.split('=')
        if (typeof temp[1] !== 'undefined') {
          sum[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1])
        }
        return sum
      }, {})
    }

    return {
      hash: hash || null,
      path: path,
      search: params || {}
    }
  },
  /**
   * Set order parameters to the GET request url
   *
   * @param {string} url
   * @param {{by: {string}, dir: {string}}}order
   * @returns {string}
   */
  setRequestOrderParams: (url, order) => {
    // Parse current URL
    let urlInfo = Helpers.parseUrl(url)
    // Set order parameters
    urlInfo.search['order[by]'] = order.by
    urlInfo.search['order[dir]'] = order.dir
    // Set the "take elements per page" value
    if ($('select[name="perPage"]').length) urlInfo.search['take'] = $('select[name="perPage"]').val()
    // Query string generation
    return `${urlInfo.path}?${new URLSearchParams(urlInfo.search).toString()}`
  },
  /**
   * Convert string to ascii
   *
   * @param str
   * @returns {string}
   */
  toAscii: str =>  {
    const converter = {
      'Á': 'A', 'á': 'a', 'Ä': 'A', 'ä': 'a', 'À': 'A', 'à': 'a', 'Â': 'A', 'â': 'a', 'æ': 'ae', 'ǽ': 'ae', 'Ã': 'A',
      'Å': 'A', 'Ǻ': 'A', 'Ă': 'A', 'Ǎ': 'A', 'Æ': 'AE', 'Ǽ': 'AE', 'ã': 'a', 'å': 'a', 'ǻ': 'a', 'ă': 'a', 'ǎ': 'a',
      'Ά': 'A', 'ª': 'a','α': 'a', 'ά': 'a', 'ἀ': 'a', 'ἁ': 'a', 'ἂ': 'a', 'ἃ': 'a', 'ἄ': 'a', 'ἅ': 'a', 'ἆ': 'a',
      'ἇ': 'a', 'Ἀ': 'A', 'Ἁ': 'A', 'Ἂ': 'A', 'Ἃ': 'A', 'Ἄ': 'A', 'Ἅ': 'A', 'Ἆ': 'A', 'Ἇ': 'A', 'ᾰ': 'a', 'ᾱ': 'a',
      'ᾲ': 'a', 'ᾳ': 'a', 'ᾴ': 'a', 'ᾶ': 'a', 'ᾷ': 'a', 'Ᾰ': 'A', 'Ᾱ': 'A', 'Ὰ': 'A', 'Ά': 'A', 'ᾼ': 'A', 'Ⱥ': 'A',
      'ⱥ': 'a', 'Ȧ': 'A', 'ȧ': 'a', 'Ә': 'A', 'ә': 'a',
      'Ĉ': 'C', 'Ċ': 'C', 'Ç': 'C', 'ç': 'c', 'ĉ': 'c', 'ċ': 'c', 'Ȼ': 'C', 'ȼ': 'c', 'č': 'c', 'Č': 'C', 'ć': 'c', 'Ć': 'C',
      'Ð': 'D', 'Đ': 'D', 'ð': 'd', 'đ': 'd', 'Δ': 'D', 'δ': 'd',
      'É': 'E', 'é': 'e', 'Ë': 'E', 'ë': 'e', 'È': 'E', 'è': 'e', 'Ê': 'E', 'ê': 'e', 'Ĕ': 'E', 'Ė': 'E', 'ĕ': 'e',
      'ė': 'e', 'Έ': 'E', 'ε': 'e', 'έ': 'e', 'Ȩ': 'E', 'ȩ': 'e', 'Ɇ': 'E', 'ɇ': 'e',
      'ƒ': 'f', 'φ': 'f', 'Ѳ': 'F', 'ѳ': 'f', 'Ғ': 'G', 'ғ': 'g',
      'Ĝ': 'G', 'Ġ': 'G', 'ĝ': 'g', 'ġ': 'g', 'γ': 'gh',
      'Ĥ': 'H', 'Ħ': 'H', 'ĥ': 'h', 'ħ': 'h', 'Ή': 'I',
      'Í': 'I', 'í': 'i', 'Ï': 'I', 'ï': 'i', 'Ì': 'I', 'ì': 'i', 'Î': 'I', 'î': 'i', 'Ĩ': 'I', 'Ĭ': 'I', 'Ǐ': 'I', 'Ί': 'I',
      'Į': 'I', 'Ĳ': 'Ij', 'ĩ': 'i', 'ĭ': 'i', 'ǐ': 'i', 'į': 'i', 'ĳ': 'ij', 'Ĵ': 'J', 'ĵ': 'j', 'η': 'i', 'ή': 'i',
      'ι': 'i', 'ί': 'i', 'ϊ': 'i', 'ΐ': 'i', 'Ꙗ': 'Ja', 'ꙗ': 'ja', 'Ɨ': 'I', 'ɨ': 'i', 'i': 'i', 'Ɉ': 'J', 'ɉ': 'j',
      'ĸ': 'k', 'Қ': 'Q', 'қ': 'q',
      'Ĺ': 'L', 'Ľ': 'L', 'Ŀ': 'L', 'ĺ': 'l', 'ľ': 'l', 'ŀ': 'l', 'λ': 'l', 'Ƚ': 'L', 'ƚ': 'l', 'Ḷ': 'L', 'ḷ': 'l', 'μ': 'm',
      'Ñ': 'N', 'ñ': 'n', 'ŉ': 'n', 'Ŋ': 'N', 'ŋ': 'n', 'Ǹ': 'N', 'ǹ': 'n', 'Ꞥ': 'N', 'ꞥ': 'n', 'Ṅ': 'N', 'ṅ': 'n',
      'Ṇ': 'N', 'ṇ': 'n', 'Ң': 'N', 'ң': 'n',
      'Ó': 'O', 'ó': 'o', 'Ö': 'O', 'ö': 'o', 'Ò': 'O', 'ò': 'o', 'Ô': 'O', 'ô': 'o', 'Õ': 'O', 'Ō': 'O', 'Ŏ': 'O',
      'Ǒ': 'O', 'Ő': 'O', 'Ơ': 'O', 'Ø': 'O', 'Ǿ': 'O', 'Œ': 'OE', 'õ': 'o', 'ō': 'o', 'ŏ': 'o', 'ǒ': 'o', 'ő': 'o',
      'ơ': 'o', 'ø': 'o', 'ǿ': 'o', 'º': 'o', 'œ': 'oe', 'Ό': 'O', 'Ω': 'O', 'ό': 'o', 'ω': 'o', 'Ǫ': 'O', 'ǫ': 'o',
      'Ɵ': 'O', 'ɵ': 'o', 'Ȯ': 'O', 'ȯ': 'o',
      'Ψ': 'Ps', 'ψ': 'ps', 'π': 'p', 'Ѱ': 'Ps', 'ѱ': 'ps',
      'Ŕ': 'R', 'Ŗ': 'R', 'ŕ': 'r', 'ŗ': 'r', 'ρ': 'r',
      'Ŝ': 'S', 'Ș': 'S', 'ŝ': 's', 'ș': 's', 'ſ': 's', 'Σ': 'S', 'σ': 's', 'ς': 's', 'Ŝ̀': 'S', 'Ꞩ': 'S', 'ꞩ': 's',
      'Ṡ': 'S', 'ṡ': 's', 'Ṣ': 'S', 'ṣ': 's', 'š': 's', 'Š': 'S',
      'Ţ': 'T', 'Ț': 'T', 'Ŧ': 'T', 'Þ': 'Th', 'ţ': 't', 'ț': 't', 'ŧ': 't', 'þ': 'th', 'Θ': 'Th', 'τ': 't',
      'ϑ': 'th', 'θ': 'th', 'Ⱦ': 'T', 'ⱦ': 't', 'Ṫ': 'T', 'ṫ': 't', 'Ṭ': 'T', 'ṭ': 't',
      'Ú': 'U', 'ú': 'u', 'Ü': 'U', 'ü': 'u', 'Ù': 'U', 'ù': 'u', 'Û': 'U', 'û': 'u', 'Ũ': 'U', 'Ŭ': 'U', 'Ű': 'U', 'Ų': 'U',
      'Ư': 'U', 'Ǔ': 'U', 'Ǖ': 'U', 'Ǘ': 'U', 'Ǚ': 'U', 'Ǜ': 'U', 'ũ': 'u', 'ŭ': 'u', 'ű': 'u', 'ų': 'u', 'ư': 'u', 'ǔ': 'u',
      'ǖ': 'u', 'ǘ': 'u', 'ǚ': 'u', 'ǜ': 'u', 'Ʉ': 'U', 'ʉ': 'u', 'Ʊ': 'U', 'ʊ': 'u', 'Ө': 'O', 'ө': 'o',
      'β': 'v', 'ϐ': 'v',
      'Ŵ': 'W', 'ŵ': 'w', 'Ώ': 'W', 'ώ': 'w', 'Ẁ': 'W', 'ẁ': 'w', 'Ẃ': 'W', 'ẃ': 'w', 'Ẅ': 'W', 'ẅ': 'w',
      'Ξ': 'Ks', 'ξ': 'ks', 'Ѯ': 'Ks', 'ѯ': 'ks',
      'Ý': 'Y', 'ý': 'y', 'Ÿ': 'Y', 'Ŷ': 'Y', 'ÿ': 'y', 'ŷ': 'y', 'ϒ': 'Y', 'Ύ': 'Y', 'Ϋ': 'Y', 'ύ': 'y', 'ΰ': 'y',
      'ϋ': 'y', 'Ɏ': 'Y', 'ɏ': 'y', 'Ẏ': 'Y', 'ẏ': 'y', 'Ȳ': 'Y', 'ȳ': 'y', 'Ұ': 'U', 'Ү': 'U', 'ұ': 'u', 'ү': 'u',
      'ζ': 'z', 'Ẑ': 'Z', 'ẑ': 'z', 'Ƶ': 'Z', 'ƶ': 'z', 'Ẓ': 'Z', 'ẓ': 'z',

      'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Ѓ': 'Gj', 'Е': 'E', 'Ж': 'Z', 'З': 'Z', 'Ѕ': 'Dz', 'И': 'I',
      'Ј': 'j', 'К': 'K', 'Л': 'L', 'Љ': 'Lj', 'М': 'M', 'Н': 'N', 'Њ': 'Nj', 'О': 'O', 'П': 'P', 'Р': 'R', 'С': 'S',
      'Т': 'T', 'Ќ': 'Kj', 'У': 'U', 'Ф': 'F', 'Х': 'X', 'Ц': 'C', 'Ч': 'C', 'Џ': 'Dz', 'Ш': 'S', 'а': 'a', 'б': 'b',
      'в': 'v', 'г': 'g', 'д': 'd', 'ѓ': 'gj', 'е': 'e', 'ж': 'z', 'з': 'z', 'ѕ': 'dz', 'и': 'i', 'ј': 'j', 'к': 'k',
      'л': 'l', 'љ': 'lj', 'м': 'm', 'н': 'n', 'њ': 'nj', 'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'ќ': 'kj',
      'у': 'u', 'ф': 'f', 'х': 'x', 'ц': 'c', 'ч': 'c', 'џ': 'dz', 'ш': 's', 'Й': 'i', 'Щ': 'Shh', 'Ъ': '', 'Ь': '',
      'Ю': 'Iu', 'Я': 'Ia', 'й': 'i', 'щ': 'shh', 'ъ': '', 'ь': '', 'ю': 'iu', 'я': 'ia', 'Ё': 'E', 'ё': 'e', 'Ы': 'Y',
      'ы': 'y', 'Э': 'E', 'э': 'e', 'І': 'I', 'і': 'i', 'Ѣ': 'E', 'ѣ': 'e', 'Є': 'Je', 'є': 'je', 'Ґ': 'G', 'ґ': 'g',
      'Ї': 'Yi', 'ї': 'yi',

      'अ': 'a', 'आ': 'aa', 'ए': 'e', 'ई': 'ii', 'ऍ': 'ei', 'ऎ': 'ae', 'ऐ': 'ai', 'इ': 'i', 'ओ': 'o', 'ऑ': 'oi', 'ऒ': 'oii',
      'ऊ': 'uu', 'औ': 'ou', 'उ': 'u', 'ब': 'B', 'भ': 'Bha', 'च': 'Ca', 'छ': 'Chha', 'ड': 'Da', 'ढ': 'Dha', 'फ': 'Fa',
      'फ़': 'Fi', 'ग': 'Ga', 'घ': 'Gha', 'ग़': 'Ghi', 'ह': 'Ha', 'ज': 'Ja', 'झ': 'Jha', 'क': 'Ka', 'ख': 'Kha', 'ख़': 'Khi',
      'ल': 'L', 'ळ': 'Li', 'ऌ': 'Li', 'ऴ': 'Lii', 'ॡ': 'Lii', 'म': 'Ma', 'न': 'Na', 'ङ': 'Na', 'ञ': 'Nia', 'ण': 'Nae',
      'ऩ': 'Ni', 'ॐ': 'oms', 'प': 'Pa', 'क़': 'Qi', 'र': 'Ra', 'ऋ': 'Ri', 'ॠ': 'Ri', 'ऱ': 'Ri', 'स': 'Sa', 'श': 'Sha',
      'ष': 'Shha', 'ट': 'Ta', 'त': 'Ta', 'ठ': 'Tha', 'द': 'Tha', 'थ': 'Tha', 'ध': 'Thha', 'ड़': 'ugDha', 'ढ़': 'ugDhha',
      'व': 'Va', 'य': 'Ya', 'य़': 'Yi', 'ज़': 'Za',

      'Ա': 'A', 'Բ': 'B', 'Գ': 'G', 'Դ': 'D', 'Ե': 'E', 'Զ': 'Z', 'Է': 'E', 'Ը': 'Y', 'Թ': 'Th', 'Ժ': 'Zh', 'Ի': 'I',
      'Խ': 'Kh', 'Ծ': 'Ts', 'Կ': 'K', 'Հ': 'H', 'Ձ': 'Dz', 'Ղ': 'Gh', 'Ճ': 'Tch', 'Մ': 'M', 'Յ': 'Y', 'Ն': 'N', 'Շ': 'Sh',
      'Ո': 'Vo', 'Չ': 'Ch', 'Պ': 'P', 'Ջ': 'J', 'Ռ': 'R', 'Ս': 'S', 'Վ': 'V', 'Տ': 'T', 'Ր': 'R', 'Ց': 'C', 'Ւ': 'u',
      'Փ': 'Ph', 'Ք': 'Q', 'և': 'ev', 'Օ': 'O', 'Ֆ': 'F', 'ա': 'a', 'բ': 'b', 'գ': 'g', 'դ': 'd', 'ե': 'e', 'զ': 'z',
      'է': 'e', 'ը': 'y', 'թ': 'th', 'ժ': 'zh', 'ի': 'i', 'լ': 'l', 'խ': 'kh', 'ծ': 'ts', 'կ': 'k', 'հ': 'h', 'ձ': 'dz',
      'ղ': 'gh', 'ճ': 'tch', 'մ': 'm', 'յ': 'y', 'ն': 'n', 'շ': 'sh', 'ո': 'vo','չ': 'ch', 'պ': 'p', 'ջ': 'j', 'ռ': 'r',
      'ս': 's', 'վ': 'v', 'տ': 't', 'ր': 'r', 'ց': 'c', 'ւ': 'u', 'փ': 'ph', 'ք': 'q', 'օ': 'o', 'ֆ': 'f', 'Ž': 'Z',
      'Ň': 'N', 'Ş': 'S', 'ž': 'z', 'ň': 'n', 'ş': 's', 'ı': 'i', 'İ': 'I', 'ğ': 'g', 'Ğ': 'G', 'Ē': 'E', 'ē': 'e',

      'Ǳ': 'DZ', 'ǲ': 'Dz', 'ǳ': 'dz', 'Ǉ': 'LJ', 'ǈ': 'Lj', 'ǉ': 'lj', 'Ǌ': 'NJ', 'ǋ': 'Nj', 'ǌ': 'nj',
      'ა': 'a', 'ბ': 'b', 'გ': 'g', 'დ': 'd', 'ე': 'e', 'ვ': 'v', 'ზ': 'z', 'თ': 't', 'ი': 'i', 'კ': 'k', 'ლ': 'l',
      'მ': 'm', 'ნ': 'n', 'ო': 'o', 'პ': 'p', 'ჟ': 'zh', 'რ': 'r', 'ს': 's', 'ტ': 't', 'უ': 'u', 'ფ': 'f', 'ქ': 'q',
      'ღ': 'gh', 'ყ': 'y', 'შ': 'sh', 'ჩ': 'ch', 'ც': 'ts', 'ძ': 'dz', 'წ': 'ts', 'ჭ': 'ch', 'ხ': 'kh', 'ჯ': 'j', 'ჰ': 'h',
      'Ѵ': 'I', 'ѵ': 'i', 'Ѥ': 'Je', 'ѥ': 'je', 'Ꙋ': 'U', 'ꙋ': 'u', 'Ѡ': 'O', 'ѡ': 'o', 'Ѿ': 'Ot', 'ѿ': 'ot', 'Ѫ': 'U',
      'ѫ': 'u', 'Ѧ': 'Ja', 'ѧ': 'ja', 'Ѭ': 'Ju', 'ѭ': 'ju', 'Ѩ': 'Ja', 'ѩ': 'Ja',
    }
    str = str.trim().split('');
    let result = '';
    for (let char in str) {
      result += typeof converter[str[char]] !== 'undefined' ? converter[str[char]] : str[char];
    }
    return result.toLowerCase().replace(/[^-a-z0-9_\/\.\#]/g, '-')
  }
}
/**
 * Check the arrays are equal
 *
 * @param {Array} arr1
 * @param {Array} arr2
 * @returns {boolean}
 */
export function arrayEquals(arr1, arr2) {
  if (!arr1 || !arr2 || (arr1.length !== arr2.length)) return !1;

  for (let i, n = arr1.length; i > n; i++) {
    if (arr1[i] instanceof Array && arr2[i] instanceof Array) {
      if (!this.arrayEquals(arr1[i], arr2[i])) return !1;
    } else if ((arr1[i] !== arr2[i])) {
      return !1;
    }
  }
  return !0;
}