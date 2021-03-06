<template>

  <div>
    <div v-for="task in tasks" :key="task.id" >
      <task-component
        @showEditTaskModal="showEditTaskModal"
        @showCompleteTaskModal="showCompleteTaskModal"
        :task="task">
      </task-component>
    </div>

    <!-- modal -->
    <modal name="completeTask" :adaptive="true" height="auto">
      <div class="p-10">

        <header class="section py-6 mt-4 mb-6" style="background: url('/images/splash.svg') 100px 4px no-repeat;">
          <h1 class="text-grey-darker font-bold text-center text-2xl mb-4">Complete the task?</h1>
        </header>

        <div class="flex mt-auto">
          <button @click.prevent="$modal.hide('completeTask')" class="flex-1 btn ml-auto mr-2 text-grey-darker text-lg hover:border-blue hover:text-blue rounded-lg py-1 px-4 border-1 border-grey">Cancel</button>
          <button
            @click="onComplete"
            :class="loading ? 'loader' : ''"
            class="flex-1 btn py-1 px-4 text-lg button rounded-lg text-white hover:bg-blue-dark"
            >Complete
          </button>
        </div>
      </div>
    </modal>
    <edit-task></edit-task>
  </div>

</template>


<script>

import TaskComponent from './TaskComponent.vue';
import EditTask from './EditTask.vue';

export default {
    props: ['projecttasks', 'project'],

    components: { TaskComponent, EditTask },

    created() {
      this.tasks = this.projecttasks;
      events.$on('addedtask', (data) => this.onAddedTask(data));

      window.Echo.channel('tasks').listen('TaskCreated', e => {
        if(this.project.id == e.task.project.id) {
          this.onCreatedEventBroadcasted(e);
        }
      });

      window.Echo.channel('tasks').listen('TaskUpdated', e => {
        if(this.project.id == e.task.project.id) {
          this.onUpdatedEventBroadcasted(e);
        }
      });

    },

    data() {
      return {
        tasks: [],
        taskForCompleting: null,
        taskForEditing: null,
        loading: false
      }
    },

    methods: {
      showCompleteTaskModal(data) {
        this.taskForCompleting = data;
        this.$modal.show('completeTask')
      },
      showEditTaskModal(data){
        this.taskForEditing = data;
        this.$modal.show('editTaskModal', {'taskForEditing': data});
      },
      onAddedTask(newTask) {
        this.sortTasks(newTask);
      },
      onComplete() {
        this.loading = true;
        axios.post(`/tasks/${this.taskForCompleting.id}`, {'completed': true})
        .then((data) => {
            this.$modal.hide('completeTask');
            this.taskForCompleting = null;
            this.tasks = this.tasks.map(task => {
              if(task.id == data.data.id) {
                task.completed = true;
              }
              return task;
            });
            flash('You completed the task! Congrats!!!', 'green');
            events.$emit('completedtask', data.data);
            this.loading = false;
          })
        .catch(error => {
          this.loading = false;
          flash('Ooops! Something went wrong!', 'red');
          $modal.hide('completeTask')
        });
      },
      onCreatedEventBroadcasted(e){
          this.sortTasks(e.task);
          flash( `${e.activity.user.name} has created a new task!`, 'green');
      },
      onUpdatedEventBroadcasted(e) {
        var item = this.tasks.find((element) => {return element.id == e.task.id });

        item.completed = e.task.completed;
        item.created_at = e.task.created_at;
        item.deleted_at = e.task.deleted_at;
        item.end = e.task.end;
        item.id = e.task.id;
        item.project_id = e.task.project_id;
        item.start = e.task.start;
        item.title = e.task.title;
        item.updated_at = e.task.updated_at;

        if (e.task.completed == true) {
          flash( `${e.activity.user.name} has completed the task!`, 'green');          
        } else {
          flash( `${e.activity.user.name} has updated the task!`, 'green'); 
        }
      },

      sortTasks(newTask) {
        
        if(this.tasks.length == 0) {
          this.tasks.push(newTask);
          return;
        } 
        if(this.tasks[0].start > newTask.start) {
          this.tasks.unshift(newTask);
          return;
        }

        let index = 0;
        for (var i = 0; i < this.tasks.length; i++) {
          if(this.tasks[i+1] == null) {
            index = i;
            break;
          } else {
            if(this.tasks[i].start < newTask.start &&  this.tasks[i+1].start > newTask.start) {
              index = i;
              break;
            }

          } 
        }
        this.tasks.splice(index + 1, 0, newTask);
      }
    },

}


</script>
