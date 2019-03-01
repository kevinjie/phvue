import axios from 'axios';
import Qs from 'qs';

const common = {
  getType: (obj) => {
    return obj.__proto__.constructor.name;
  },
  isEmpty: function isEmpty(data) {
    if (data === null || data === "" || !data) {
      return true;
    } else if (data.__proto__.constructor.name === "Array") {
      if (data.length <= 0) {
        return true;
      }
    } else if (data.__proto__.constructor.name === "Object") {
      if (Object.keys(data).length <= 0) {
        return true;
      }
    }

    return false;
  },
  axiosPost(url = '', params = {}, successFun = (res) => {
    console.log(res)
  }, errorFun = (err) => {
    console.log(err)
  }, header = {}) {
    let headersObject = {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest'
    };

    if (common.getType(params) == 'FormData') {
      params.append('timetamp', new Date().getTime());
      params = params;
    } else {
      params['timetamp'] = new Date().getTime();
      params = Qs.stringify(params);
    }

    if (!common.isEmpty(header)) {
      Object.assign(headersObject, header);
    }

    axios.post(url, params, {
        headers: headersObject
      })
      .then(successFun)
      .catch(errorFun);
  },
  axiosGet(url, params) {
    if (!url) return
    return axios({
      method: 'get',
      url: url,
      params,
      timeout: 10000
    }).then(response => {
      return common.checkStatus(response)
    }).then(res => {
      return common.checkCode(res);
    })
  }
};

export default common;
