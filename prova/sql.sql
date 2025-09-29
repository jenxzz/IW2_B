CREATE DATABASE cadastro;
USE cadastro;

CREATE TABLE usuarios (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NOME VARCHAR(100) NOT NULL,
    GMAIL VARCHAR(100) NOT NULL,
    SENHA VARCHAR(100) NOT NULL,
    ATIVO BOOLEAN NOT NULL
);


 const tr =document.createElement("tr");

                tr.innerHTML =`
                                <td>${usuario.ID}</td>
                                <td>${usuario.NOME}</td>
                                <td>${usuario.GMAIL}</td> 
                                <td>${usuario.SENHA}</td> 
                                <td>${usuario.ATIVO ==1 ? "sim" :"Não"}</td>
                                <td>
                                  <button onclick='editar(${JSON.stringify(usuario)})'>Editar</button>
                                  <button onclick='excluir(${usuario.ID})'>Excluir</button>
                                </td> 
                              `;
                  tbody.appendChild(tr);
              });

            })
            
            const tbody=document.querySelector("#tabela tbody");
            tbody.innerHTML = "";

      }

      function carregarPesquisa(){
        const valor = document.getElementById("pesquisa").value;
        carregar(valor);
      }
      
      async function salvar(){
        const id=document.getElementById("tenisId").value;
        const nome=document.getElementById("nomeTenis").value;
        const quantidade=document.getElementById("marcaTenis").value;
        const dia=document.getElementById("precoTenis").value;
        const ativo=document.getElementById("disponivelTenis").value;

        const payload = {ID: id, NOME: nome,QUANTIDADE:quantidade, DIA:dia, ATIVO : ativo};

        if(id){
          await fetch (url_api,{method: "PUT", body: JSON.stringify(payload)});
        }
        else{
          await fetch (url_api,{method: "POST", body: JSON.stringify(payload)});
        }

        limpar();
        carregar();
      }

      function editar(usuario){
        document.getElementById("tenisId").value = usuario.id;
        document.getElementById("nomeTenis").value= usuario.nome;
        document.getElementById("marcaTenis").value= usuario.quantidade;
        document.getElementById("precoTenis").value= usuario.dia;
        document.getElementById("disponivelTenis").value= usuario.ativo;
      }

      console.log(editar);

      async function excluir(id){
          if (confirm("deseja mesmo excluir este usuario?")){
            await fetch (url_api + "?tenisId=" + id, {method: "DELETE"});
            carregar();
          }
          console.log(excluir);
      }
      function limpar(){
        const id=document.getElementById("tenisId").value = "";
        const nome=document.getElementById("nomeTenis").value= "";
        const quantidade=document.getElementById("marcaTenis").value = "";
        const dia=document.getElementById("precoTenis").value = "";
        const ativo=document.getElementById("disponivelTenis").value = "1 ";
        }
        carregar();