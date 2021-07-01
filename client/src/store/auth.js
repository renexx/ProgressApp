import axios from 'axios'  
export default {
    namespaced:true,

  state: {
    //stav aplikacie
    token: null,
    user: null,

  },
  getters:{
    authenticated(state){
        return state.token && state.user
    },
    user(state){
        return state.user
    }
  },
  mutations: {
    //updatuje stav
    SET_TOKEN(state,token){
        state.token = token
    },
    SET_USER(state,data){
        state.user = data
    }
    
  },
  actions: {
    //request api
    async logIn({dispatch}, credentials){
      try{
        let response = await axios.post('api/v1/login',credentials) 
        return dispatch('attempt',response.data.token);

      }catch(e){
        alert(e.response.data.message)
      }
       
    },
    async attempt ({commit, state}, token){
        if(token){
            commit('SET_TOKEN',token)
        }
        if(!state.token){
            return
        }

        try{
            let response = await axios.get('api/v1/me')
            commit('SET_USER',response.data)
        }catch(e){
            commit('SET_TOKEN',null)
            commit('SET_USER',null)
        }
    },
    signOut({commit}){
      return axios.post('api/v1/logout').then(() =>{
        commit('SET_TOKEN',null)
        commit('SET_USER',null)
      })
    },
    unRegister({commit}){
      if(window.confirm("Do yo want to delete your account? Are you sure?")){
        return axios.delete('api/v1/unregister').then(() =>{
          commit('SET_TOKEN',null)
          commit('SET_USER',null)
        })
      }
      
    }
  }
}
