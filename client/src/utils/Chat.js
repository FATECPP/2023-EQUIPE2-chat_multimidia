import axios from "axios";

export default class Chat {
  static urlBase = import.meta.env.VITE_SERVER_BASE_URL;

  static getUsers() {
    return axios.get(this.urlBase + "users").then((response) => {
      // response.data.forEach(user => {
      //   user.groups.forEach((group, index) => {
      //     let id = group;
      //     user.groups[index] = Chat.getGroup(id);
      //     //console.log(index+" / " + group); 
      //   });
      //   Promise.all(user.groups)
      //     .then(responses => {
      //       // 'responses' é um array contendo as respostas de cada requisição
      //       // console.log(responses);
      //       user.groups = responses;
      //       console.log(user)
      //     })
      //     .catch(error => {
      //       // Tratar erros aqui, se necessário
      //       console.error(error);
      //     });
      //   //console.log(user.groups)
      // });
      return response.data;
    })
      .catch((error) => {
        throw error;
      });
  }

  static getGroups(id) {
    return axios
      .get(this.urlBase + `groups/${id}`)
      .then((response) => {
        return response.data;
      })
      .catch((error) => {
        throw error;
      });
  }

  static getGroup(id) {
    return axios
      .get(this.urlBase + `groups/${id}`)
      .then((response) => {
        return response.data;
      })
      .catch((error) => {
        throw error;
      });
  }

  static getUsersOfGroup(id) {
    return axios
      .get(this.urlBase + `groups/users/${id}`)
      .then((response) => {
        return response.data;
      })
      .catch((error) => {
        throw error;
      });
  }
}
