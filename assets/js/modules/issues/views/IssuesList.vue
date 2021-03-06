<!--
  -
  - @copyright 2020 Mohammadi El Youzghi. All rights reserved
  - @author    Mohammadi El Youzghi (mo.elyouzghi@gmail.com)
  -
  - @link      https://github.com/ayrad
  -
  - @licence   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
  -
  -->

<template>
    <v-container>
        <h2 class="d-block pt-4">Issues</h2>

        <v-row class="align-center justify-start">
            <v-col cols="3">
                <v-overflow-btn :items="activeProjects" :loading="loadingProjects" append-icon="mdi-folder-multiple"
                                editable
                                item-text="name" item-value="id" label="Project" segmented
                                v-model="selectedProject"/>
            </v-col>
        </v-row>

        <v-data-table :footer-props="{itemsPerPageOptions: [10, 50, 100, 200, -1]}" :headers="issuesHeaders" :items="issues"
                      :items-per-page="100" :loading="loadingIssues"
                      @click:row="handleClick" class="elevation-1">
            <template v-slot:item.seenAt="{ item }">{{ item.seenAt|timeago }}</template>
            <template v-slot:item.exception.className="{ item }">
                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <abbr title="" v-on="on">{{ item.exception.className }}</abbr>
                    </template>
                    <span>{{ item.exception.class }}</span>
                </v-tooltip>
            </template>
            <template v-slot:item.exception.message="{ item }">
                <template v-if="item.exception.code">(code: {{ item.exception.code }})</template>
                {{ item.exception.message }}
            </template>
            <template v-slot:item.resolved="{ item }">
                <v-simple-checkbox disabled v-model="item.resolved"></v-simple-checkbox>
            </template>
            <template v-slot:item.pinned="{ item }">
                <v-icon v-if="item.pinned" class="mr-1" small>mdi-pin</v-icon>
            </template>
        </v-data-table>
    </v-container>
</template>

<script>
    import {mapMutations} from 'vuex';

    export default {
        name: "IssuesList",
        data: () => ({
            loadingProjects: true,
            loadingIssues: true,
            activeProjects: [],
            selectedProject: null,
            issuesHeaders: [
                {text: '', value: 'pinned'},
                {text: 'Seen At', value: 'seenAt'},
                {text: 'Request Method', value: 'request.method'},
                {text: 'Request URL', value: 'request.url'},
                {text: 'Exception Class', value: 'exception.className'},
                {text: 'Exception Message', value: 'exception.message'},
                {text: 'Resolved', value: 'resolved'},
            ],
            issues: [],
        }),
        created() {
            const projectId = this.$route.params.projectId || null;
            this.fetchProjectsList(projectId);
            this.fetchIssuesList(projectId);
        },
        watch: {
            selectedProject: function (newProjectId) {
                if (this.$route.params.projectId === newProjectId) {
                    return;
                }

                this.$router.push({name: 'project-issues-list', params: {projectId: newProjectId}});
            },
        },
        methods: {
            fetchProjectsList(projectId) {
                this.$store.dispatch('projects/fetchList')
                    .then(response => {
                        if (!response.data.length) {
                            this.$router.push({name: 'projects-list'});
                        }

                        this.activeProjects = response.data.filter(project => !project.archived);
                        if (!this.activeProjects.length) {
                            this.$router.push({name: 'projects-list'});
                        }
                        const filtered = this.activeProjects.filter((project) => project.id === projectId);
                        this.selectedProject = (filtered.length ? filtered[0].id : this.activeProjects[0].id);
                    })
                    .catch(error => {
                        this.setSnackMessage({
                            message: "Unexpected error occurred. Please, try again.",
                            color: "error"
                        });
                    })
                    .then(() => {
                        this.loadingProjects = false;
                    });
            },
            fetchIssuesList(projectId) {
                if (!projectId) {
                    return;
                }

                this.loadingIssues = true;

                this.$store.dispatch('issues/fetchList', projectId)
                    .then(response => {
                        this.issues = response.data;
                    })
                    .catch(error => {
                        this.setSnackMessage({
                            message: "Unexpected error occurred. Please, try again.",
                            color: "error"
                        });
                    })
                    .then(() => {
                        this.loadingIssues = false;
                    });
            },
            handleClick(issue, row) {
                this.$router.push({name: 'issue-detail', params: {id: issue.id}});
            },
            ...mapMutations({
                setSnackMessage: 'setSnackMessage'
            })
        }
    }
</script>

<style>
    .v-data-table tbody tr {
        cursor: pointer;
    }
</style>
