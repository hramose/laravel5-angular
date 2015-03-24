var pages = [
	{
		name		: 'users_group',
		title		: 'Users',
		icon		: 'fa-wrench',
		subMenu		: [
			{
			name		: 'users',
			title		: 'Users',
			url 		: '/users', 
			templateUrl : 'views/users.html',
			prefix		: 'users',
			icon		: 'fa-user',
			fields		: [
					{
						'name'		:'username', 
						'editable' 	: false
					},
					{
						'name'		:'name', 
						'editable' 	: true
					},
					{
						'name'		:'email', 
						'editable' 	: true
					},
					{
						'name'		:'password', 
						'editable' 	: false
					},
					{
						'name'		:'role', 
						'editable' 	: true,
						lookup : {
							url 	: "roles/data",
							itemUrl : "roles/item",
							field 	: "name"
						}
					}
				]
			},
			{
			name		: 'roles',
			title		: 'Roles',
			url 		: '/roles', 
			templateUrl : 'views/roles.html',
			prefix		: 'roles',
			icon		: 'fa-users',
			fields		: [
					{
						'name'		:'name', 
						'editable' 	: false
					},
					{
						'name'		:'permissions', 
						'editable' 	: true
					}
				]
			},
		]
	}
];