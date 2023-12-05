<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    @vite(['resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
</head>
<style>
    body {
        background-color: #eee;
        font-family: 'Popppins', sans-serif;
        font-weight: 300;
    }

    .btn {
        border-radius: 25px;
    }

    .card {
        border: none;
    }

    .icon {
        height: 40px;
        width: 40px;
        justify-content: center;
        align-items: center;
        background-color: #eee;
        margin-right: 10px;
        border-radius: 5px;
    }

    .text-one {
        height: 40px;
        width: 40px;
        display: flex;
        background-color: #ffe7cc;
        color: #ffa03a;
        font-weight: 700;
        border-radius: 50%;
        justify-content: center;
        align-items: center;
    }

    .text-two {
        height: 40px;
        width: 40px;
        display: flex;
        background-color: #e2f5fa;
        color: #6ecce6;
        font-weight: 700;
        border-radius: 50%;
        justify-content: center;
        align-items: center;
    }

    .text-three {
        height: 40px;
        width: 40px;
        display: flex;
        background-color: #572ce86b;
        color: #572ce8;
        font-weight: 700;
        border-radius: 50%;
        justify-content: center;
        align-items: center;
    }

    .search-bar {
        position: relative;
    }

    .dot {
        background-color: #d9dada;
        border-radius: 50%;
        width: 7px;
        height: 7px;
        display: flex;
        margin: 0px 7px 0px 7px;
    }

    .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        width: 400px;
    }

    .modal-header {
        text-align: center;
    }

    .modal-footer {
        text-align: center;
        margin-top: 20px;
    }

    
</style>
<body>

    <div x-data="tasks()" x-init="getWeeklyTasks()" class="container" >
        <dix class="row d-flex justify-content-center mt-5">
            <div class="col-8">
                <div  class="card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h4 style="font-weight: 700;">Weekly Tasks</h4>
                        </div>
                        <div class="d-flex flex-row">
                            <button type="button" class="btn btn-light" style="margin-right: 10px;">Active</button>
                            <button type="button" class="btn btn-primary"><i class="fas fa-plus" style="color: #f4f6fb;"></i> New</button>
                        </div>
                    </div>

                    <!--  -->
                    <div class="search-bar" style="position: relative;">
                        <i class="fa fa-search" style="position: absolute; top: 10px; left: 4px; color: #c1c2c2;"></i>
                        <input type="search" class="form-control" placeholder="Search Tasks..." style="border: none; padding-left: 30px;" x-model="search">
                        <hr class="mt-1">
                    </div>

                    <template x-for="tasks in filteredTasks">
                      <div class="d-flex align-items-center py-3">
                          <div class="flex-shrink-0 me-3">
                              <div class="icon d-flex align-items-center">
                                  <i class="fas fa-star" style="color: #ffa03a;"></i>
                              </div>
                          </div>
                          <div class="flex-grow-1">
                              <div>
                                  <h5 x-text="tasks.task" class="fs-14 mb-1"></h5>
                                  <div class="d-flex flex-row align-items-center time-text">
                                      <small x-text="tasks.user.name"></small>
                                      <span class="dot"></span>
                                      <small>Created <span x-text="tasks.created_at"></span></small>
                                      <span class="dot"></span>
                                      <small>Comments(<span x-text="tasks.number_of_comments"></span>)</small>
                                  </div>
                                  
                              </div>
                          </div>
                          <div class="flex-shrink-0 ms-2">
                          <a href="#"><i  x-on:click="showCommentModal()" class="fa fa-comment" aria-hidden="true"></i></a>
                          </div>
                      </div>
                    </template>          
                    
                    <div 
                        x-show="commentModal"
                        x-cloak
                        @click.away="commentModal = false"
                        x-transition>
                        <div class="modal-overlay"></div>
                        <div class="modal-container">
                            <div class="modal-header">
                                <h2>Add Comment</h2>
                            </div>
                            <div class="modal-body">
                                <form>
                                    
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Comments</label>
                                        <textarea class="form-control" id="comment_field" x-model="commentForm.comment" rows="3"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <div  class="form-group">
                                    <button x-on:click="submitComment()" class="btn btn-primary mb-2">Submit Comment</button>
                                </div>
                                &nbsp;&nbsp;&nbsp;
                                <div  class="form-group">
                                    <button x-on:click="commentModal = false" class="btn btn-dark mb-2">close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>

    function tasks() {
        return {
            weekly_tasks: [],
            search: '',
            commentModal: false,
            commentForm:{

                comment: ""

            },
    
            getWeeklyTasks: async function() {
                console.log("fetching results");
                const response = await fetch(
                "http://127.0.0.1:80/api/tasks"
                );
                const data = await response.json();;
                this.weekly_tasks = data.data
    
        
            },

            get filteredTasks () {

                if (this.search === '') {
				    return this.weekly_tasks;
                }
                return this.weekly_tasks.filter((task) => {
                    return task.task
                        .replace(/ /g, '')
                        .toLowerCase()
                        .includes(this.search.replace(/ /g, '').toLowerCase());
                });
            },


            showCommentModal: function() {

                this.commentModal = true;

            },

            submitComment: function(){


                fetch("http://127.0.0.1:80/api/submit-comment", {

                    method: "POST",
                    headers: { 
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(this.commentForm),
                })
                .then((response) => {
                    console.log(response);

                    if(response.status == 200){

                        this.commentModal =  false;
                    }
                    else if(response.status == 422){
                        alert("Please fill required fields");
                    }
                    else if(response.status == 403){
                        alert("Unauthorised. Please login");
                    }
                })
                
                .catch((error) => {
                    console.log(error);
                });

            }

        };
      }

</script>