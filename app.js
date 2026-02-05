new Vue({
    el: '#app',
    vuetify: new Vuetify({
        theme: {
            themes: {
                light: {
                    primary: '#1976D2',
                    secondary: '#424242',
                    accent: '#82B1FF',
                    error: '#FF5252',
                    info: '#2196F3',
                    success: '#4CAF50',
                    warning: '#FFC107',
                },
            },
        },
    }),
    data: {
        selectedItem: 0, // Default to Dashboard
        dialog: false,
        courseDialog: false,
        students: [],
        courses: [],
        search: '',
        courseSearch: '',
        loading: false,
        form: { student_id: '', name: '', email: '', course: '' },
        courseForm: { course_code: '', course_name: '', credits: '' },
        headers: [
            { text: 'ID', value: 'student_id', width: '100px' },
            { text: 'Name', value: 'name' },
            { text: 'Email', value: 'email' },
            { text: 'course', value: 'course' },
            { text: 'Actions', value: 'actions', sortable: false, align: 'end' }
        ],
        courseHeaders: [
            { text: 'Code', value: 'course_code', width: '120px' },
            { text: 'Course Name', value: 'course_name' },
            { text: 'Credits', value: 'credits', width: '100px' },
            { text: 'Actions', value: 'actions', sortable: false, align: 'end' }
        ],
        snackbar: { show: false, text: '', color: 'success' }
    },
    computed: {
        courseNames() {
            return this.courses.map(c => c.course_name);
        }
    },
    mounted() {
        this.fetchStudents();
        this.fetchCourses();
    },
    methods: {
        fetchStudents() {
            this.loading = true;
            fetch('api.php')
                .then(res => res.json())
                .then(data => {
                    this.students = data;
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Error:', err);
                    this.loading = false;
                    this.showSnackbar('Failed to load students. Ensure api.php is running.', 'error');
                });
        },
        fetchCourses() {
            this.loading = true;
            fetch('api.php?resource=courses')
                .then(res => res.json())
                .then(data => {
                    this.courses = data;
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Error:', err);
                    this.loading = false;
                    this.showSnackbar('Failed to load courses.', 'error');
                });
        },
        addStudent() {
            if(!this.form.name || !this.form.student_id) {
                this.showSnackbar('Please fill required fields', 'warning');
                return;
            }
            this.loading = true;
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.form)
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                if(data.success) {
                    this.showSnackbar('Student added successfully', 'success');
                    this.dialog = false;
                    this.form = { student_id: '', name: '', email: '', course: '' };
                    this.fetchStudents();
                } else {
                    this.showSnackbar(data.message || 'Error creating student', 'error');
                }
            })
            .catch(err => {
                this.loading = false;
                this.showSnackbar('Network error', 'error');
            });
        },
        deleteStudent(id) {
            if(!confirm('Are you sure you want to delete this student?')) return;
            
            fetch('api.php?id=' + id, {
                method: 'DELETE'
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.showSnackbar('Student deleted', 'success');
                    this.fetchStudents();
                } else {
                    this.showSnackbar('Error deleting', 'error');
                }
            });
        },
        showSnackbar(text, color) {
            this.snackbar.text = text;
            this.snackbar.color = color;
            this.snackbar.show = true;
        },
        addCourse() {
            if(!this.courseForm.course_name || !this.courseForm.course_code) {
                this.showSnackbar('Please fill required fields', 'warning');
                return;
            }
            this.loading = true;
            fetch('api.php?resource=courses', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.courseForm)
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                if(data.success) {
                    this.showSnackbar('Course added successfully', 'success');
                    this.courseDialog = false;
                    this.courseForm = { course_code: '', course_name: '', credits: '' };
                    this.fetchCourses();
                } else {
                    this.showSnackbar(data.message || 'Error creating course', 'error');
                }
            })
            .catch(err => {
                this.loading = false;
                this.showSnackbar('Network error', 'error');
            });
        },
        deleteCourse(id) {
            if(!confirm('Are you sure you want to delete this course?')) return;
            
            fetch('api.php?resource=courses&id=' + id, {
                method: 'DELETE'
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.showSnackbar('Course deleted', 'success');
                    this.fetchCourses();
                } else {
                    this.showSnackbar('Error deleting', 'error');
                }
            });
        }
    }
});
