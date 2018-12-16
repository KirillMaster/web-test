$(document).ready(function () {
    var studyplanViewModel = function () {
        return new function () {
            var self = this;

            initializeViewModel.call(self, {
                page: menu.admin.main,
                mode: true
            });


            self.initial = {
                unUrlPlanId: function () {
                    var url = window.location.href;
                    var id = +url.substr(url.lastIndexOf('/') + 1);
                    if ($.isNumeric(id)) {
                        self.current.planId(id);
                        self.get.disciplines();
                        return;
                    }
                    self.errors.show('Учебный план не определен');
                    setTimeout(function () {
                        window.location.href = '/admin/main';
                    }, 5000);
                },
                disciplines: ko.observableArray([]),
                selection: ko.observable(null).extend({
                    required: true
                })
            };
            self.filter = {
                discipline: ko.observable(''),
                semester: ko.observable(''),
                clear: function () {
                    self.filter.discipline('');
                    self.filter.discipline('');
                }
            };
            self.current = {
                disciplines: ko.observableArray([]),
                discipline: ko.validatedObservable({
                    id: ko.observable(0),
                    semester: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 12,
                        number: true
                    }),
                    hoursAll: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 10000,
                        number: true
                    }),
                    hoursLecture: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 10000,
                        number: true
                    }),
                    hoursLaboratory: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 10000,
                        number: true
                    }),
                    hoursPractical: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 10000,
                        number: true
                    }),
                    hoursSolo: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 10000,
                        number: true
                    }),
                    countLecture: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 50,
                        number: true
                    }),
                    countLaboratory: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 50,
                        number: true
                    }),
                    countPractical: ko.observable(0).extend({
                        required: true,
                        min: 1,
                        max: 50,
                        number: true
                    }),
                    hasExam: ko.observable(true),
                    hasCoursework: ko.observable(true),
                    hasCourseProject: ko.observable(true),
                    hasDesignAssignment: ko.observable(true),
                    hasEssay: ko.observable(true),
                    hasHomeTest: ko.observable(true),
                    hasAudienceTest: ko.observable(true),
                    discipline: ko.observable(''),
                    disciplineId: ko.observable(0),

                }),
                planId: ko.observable()
            };
            self.alter = {
                stringify: function () {
                    var discipline = ko.mapping.toJS(self.current.discipline);
                    if (self.mode() === state.create) delete discipline.id;
                    return JSON.stringify({
                        disciplinePlan: discipline,
                        studyPlanId: self.current.planId(),
                        disciplineId: self.current.discipline().disciplineId()
                    })
                },
                fill: function (d) {
                    // console.log(d);

                    ko.mapping.fromJS(d, {}, self.current.discipline);

                    // self.current.discipline().id(d.id()).semester(d.semester())
                    //     .hoursAll(d.hoursAll()).hoursLecture(d.hoursLecture())
                    //     .hoursLaboratory(d.hoursLaboratory()).hoursPractical(d.hoursPractical())
                    //     .hoursSolo(d.hoursSolo()).countLecture(d.countLecture())
                    //     .countLaboratory(d.countLaboratory()).countPractical(d.countPractical())
                    //     .hasExam(d.hasExam()).hasCoursework(d.hasCoursework())
                    //     .hasCourseProject(d.hasCourseProject()).hasDesignAssignment(d.hasDesignAssignment())
                    //     .hasEssay(d.hasEssay()).hasHomeTest(d.hasHomeTest()).hasAudienceTest(d.hasAudienceTest())
                    //     .discipline(d.discipline()).disciplineId(d.disciplineId());
                },
                empty: function () {
                    self.current.discipline().id(0).semester('')
                        .hoursAll('').hoursLecture('').hoursLaboratory('')
                        .hoursPractical('').hoursSolo('').countLecture('')
                        .countLaboratory('').countPractical('')
                        .hasExam(true).hasCoursework(true).hasCourseProject(true)
                        .hasDesignAssignment(true).hasEssay(true).hasHomeTest(true)
                        .hasAudienceTest(true).discipline('').disciplineId(0);
                }
            };
            // self.current.hasSemester = {
            //     showItem: function (index) {
            //         if (index == 0)
            //             return true;
            //
            //         return self.current.disciplines[index - 1].semester != self.current.disciplines[index].semester;
            //     }
            // };
            self.actions = {
                show: function (data) {
                    var isCurrent = self.current.discipline().id() === data.id();
                    console.log(isCurrent, data.id(), self.current.discipline().id());
                    if (isCurrent) {
                        self.alter.empty();
                        self.mode(state.none);
                        return;
                    }
                    self.alter.fill(data);
                    self.mode(state.info);
                },
                start: {
                    create: function () {
                        self.mode() === state.create
                            ? self.mode(state.none)
                            : self.mode(state.create);
                        self.alter.empty();
                        commonHelper.buildValidationList(self.validation);
                    },
                    update: function () {
                        self.mode(state.update);
                        commonHelper.buildValidationList(self.validation);
                    },
                    remove: function () {
                        self.mode(state.remove);
                        commonHelper.modal.open('#remove-discipline-plan-modal');
                    }
                },
                end: {
                    update: function () {
                        self.current.discipline.isValid()
                            ? self.post.discipline()
                            : self.validation[$('[accept-validation]').attr('id')].open();
                    },
                    remove: function () {
                        self.post.removal();
                    }
                },
                cancel: function () {
                    if (self.mode() === state.create) {
                        self.alter.empty();
                        self.mode(state.none);
                    }
                    self.mode(state.info);
                },
                switchExam: {
                    on: function (data) {
                        data.hasExam(true);
                    },
                    off: function (data) {
                        data.hasExam(false);
                    }
                },
                switchCoursework: {
                    on: function (data) {
                        data.hasCoursework(true);
                    },
                    off: function (data) {
                        data.hasCoursework(false);
                    }
                },
                switchCourseProject: {
                    on: function (data) {
                        data.hasCourseProject(true);
                    },
                    off: function (data) {
                        data.hasCourseProject(false);
                    }
                },
                switchDesignAssignment: {
                    on: function (data) {
                        data.hasDesignAssignment(true);
                    },
                    off: function (data) {
                        data.hasDesignAssignment(false);
                    }
                },
                switchEssay: {
                    on: function (data) {
                        data.hasEssay(true);
                    },
                    off: function (data) {
                        data.hasEssay(false);
                    }
                },
                switchHomeTest: {
                    on: function (data) {
                        data.hasHomeTest(true);
                    },
                    off: function (data) {
                        data.hasHomeTest(false);
                    }
                },
                switchAudienceTest: {
                    on: function (data) {
                        data.hasAudienceTest(true);
                    },
                    off: function (data) {
                        data.hasAudienceTest(false);
                    }
                },
                sortedDiscipline : function () {
                    let oldDisciplince = ko.mapping.toJS(self.current.disciplines());
                    let disciplines = [];
                    oldDisciplince.forEach(d => {
                        if (disciplines.find(t => t.disciplineId === d.disciplineId))
                            d.disciplineVisible = false;
                        else d.disciplineVisible = true;

                        disciplines.push(d);
                    });
                    console.log(disciplines);
                    return ko.mapping.fromJS(disciplines);
                },
            };
            self.get = {
                fullList: function () {
                    var requestOptions = {
                        url: '/api/disciplines/',
                        errors: self.errors,
                        successCallback: function (data) {
                            self.initial.disciplines(data());
                        }
                    };
                    $ajaxget(requestOptions);
                },
                disciplines: function () {
                    var name = self.filter.discipline() ? '&name=' + self.filter.discipline() : '';
                    var url = '/api/plan/discipline/show' +
                        '?studyplan=' + self.current.planId() + name;

                    var requestOptions = {
                        url: url,
                        errors: self.errors,
                        data: null,
                        successCallback: function (data) {
                            self.current.disciplines(data.data());
                        }
                    };
                    $ajaxpost(requestOptions);
                }
            };
            self.post = {
                discipline: function () {
                    var requestOptions = {
                        url: self.mode() === state.create ? '/api/plan/discipline/create' : '/api/plan/discipline/update',
                        data: self.alter.stringify(),
                        errors: self.errors,
                        successCallback: function () {
                            self.mode(state.none);
                            self.alter.empty();
                            self.initial.selection(null);
                            self.get.disciplines();
                        }
                    };
                    $ajaxpost(requestOptions);
                },
                removal: function () {
                    var url = '/api/plan/discipline/delete/' + self.current.discipline().id();
                    var requestOptions = {
                        url: url,
                        data: null,
                        errors: self.errors,
                        successCallback: function () {
                            self.alter.empty();
                            self.mode(state.none);
                            self.get.disciplines();
                        }
                    };
                    $ajaxpost(requestOptions);
                }
            };


            self.initial.unUrlPlanId();
            self.get.fullList();


            self.initial.selection.subscribe(function (value) {
                if (value) {
                    self.current.discipline().disciplineId(value.id());
                    self.current.discipline().discipline(value.name());
                    return;
                }
                self.current.discipline().disciplineId(0);
                self.current.discipline().discipline('');
            });
            self.filter.discipline.subscribe(function () {
                self.mode(state.none);
                self.get.disciplines();
            });

            return returnStandart.call(self);
        };
    };

    ko.applyBindings(studyplanViewModel());
});
