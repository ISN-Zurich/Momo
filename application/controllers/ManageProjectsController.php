<?php

/** 
 * Copyright 2013, ETH Zürich
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License. 
 */

// ------------------------------------------------------------------------

use momo\projectmanager\ProjectManager;
use momo\audittrailmanager\EventDescription;
use momo\core\security\Roles;
use momo\core\security\Permissions;
use momo\core\exceptions\MomoException;
use momo\entrymanager\EntryManager;

/**
 * ManageProjectsController
 * 
 * Exposes the client actions needed to work with projects.
 * 
 * @author  Francesco Krattiger
 * @package momo.application.controllers
 */
class ManageProjectsController extends Momo_Controller {
	
	/**
	 *  Default action displays a list of all the available projects
	 */
	public function index() {
		
		// authorize call
		$this->authorize(Permissions::PROJECTS_LIST_ALL_PROJECTS);
		
		// get ref to managers needed
		$projectManager = $this->getCtx()->getProjectManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
		
		// retrieve all active projects
		$activeProjects = $projectManager->getAllProjects();
		
		// retrieve all active teams
		$activeTeams = $teamManager->getActiveTeams();
		
		// retrieve all enabled users
		$activeUsers = $userManager->getAllEnabledUsers();
		
		// prepare view data
		$data["component"] = "components/component_list_projects.php";
		$data["component_title"] = "Manage Projects";
		
		$data["component_projects_active"] = $activeProjects;
		$data["component_teams_active"] = $activeTeams;
		$data["component_users_active"] = $activeUsers;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Creates a new project
	 */
	public function createProject() {
		
		// authorize call
		$this->authorize(Permissions::PROJECTS_CREATE_PROJECT);
		
		// get ref to managers needed
		$projectManager = $this->getCtx()->getProjectManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();

		// get db connection
		$con = \Propel::getConnection(\OOBookingPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
		
			// retrieve the users indicated as part of the form
			$assignedTeams 		= $teamManager->getTeamsByIdList($this->input->post('assignedTeams'));
			$assignedUsers	 	= $userManager->getUsersByIdList($this->input->post('assignedUsers'));
				
			// create the project
			$newProject = $projectManager->createProject(	$this->input->post('projectName'),
														 	$assignedTeams,
														 	$assignedUsers,
														 	$this->input->post('enabled')
														  );
	
			//
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItemDigest($newProject->compileStateDigest());			
														  
			// add to audit trail					
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									ProjectManager::MANAGER_KEY,
									"Created Project",
									$eventDescription
								);	

			// commit TX
			$con->commit();
		}
		catch (\Exception $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("WorkplanBookingsController:createProject() - an error has occurred while while attempting to create a project.", 0, $ex);
		}								
									  
		// redisplay default view
		redirect("/manageprojects");
	}
	
	/**
	 *  Updates a project
	 */
	public function updateProject() {
		
		// authorize call
		$this->authorize(Permissions::PROJECTS_EDIT_PROJECT);
		
		// get ref to managers needed
		$projectManager = $this->getCtx()->getProjectManager();
		$teamManager 	= $this->getCtx()->getTeamManager();
		$userManager 	= $this->getCtx()->getUserManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();
		
		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\ProjectPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			
			// save pre update state
			$updateTarget = $projectManager->getProjectById($this->input->post('projectId'));
			
			// populate audit event description for pre update state
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("*** pre update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());
			
			// retrieve the users/teams to be assigned as part of the update
			$assignedTeams 		= $teamManager->getTeamsByIdList($this->input->post('assignedTeams'));
			$assignedUsers	 	= $userManager->getUsersByIdList($this->input->post('assignedUsers'));
				
			// update the project
			$updateTarget = $projectManager->updateProject(	
															$projectManager->getProjectById($this->input->post('projectId')),
															trim($this->input->post('projectName')),
														 	$assignedTeams,
														 	$assignedUsers,
														 	$this->input->post('enabled')
														  );
										  
			// populate audit event description for post update state
			$eventDescription->addDescriptionItem("*** post update", "");
			$eventDescription->addDescriptionItemDigest($updateTarget->compileStateDigest());	
			
			// add event to audit trail						
			$auditManager->addAuditEvent(	
										$this->getCtx()->getUser(),
										ProjectManager::MANAGER_KEY,
										"Updated Project",
										$eventDescription
									);	

			// commit TX
			$con->commit();
		}
		catch (\PropelException $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("ProjectManager:updateProject() - a database error has occurred while while attempting to update the project with ID: " . $projectId, 0, $ex);
		}							
			
		// redisplay default view
		redirect("/manageprojects");
	}
	
	
	/**
	 *  "Deletes" the indicated project
	 *  (see ProjectManager for further information)
	 *  
	 *  @param 	integer	$projectId		the id of the project to delete
	 */
	public function deleteProject($projectId) {
		
		// authorize call
		$this->authorize(Permissions::PROJECTS_DELETE_PROJECT);
		
		// get ref to project manager
		$projectManager = $this->getCtx()->getProjectManager();
		$auditManager = $this->getCtx()->getAuditTrailManager();

		// get db connection (for TX operations, it is best practice to specify connection explicitly)
		$con = \Propel::getConnection(\ProjectPeer::DATABASE_NAME);
		
		// start TX
		$con->beginTransaction();
 
		try {
			// archive the project
			$archivedProject = $projectManager->archiveProject($projectManager->getProjectById($projectId));
			
			//
			// write event to audit trail
			$eventDescription = new EventDescription();
			$eventDescription->addDescriptionItem("Project Name", $archivedProject->getName());
																																
			// add event to audit trail						
			$auditManager->addAuditEvent(	
									$this->getCtx()->getUser(),
									ProjectManager::MANAGER_KEY,
									"Deleted Project",
									$eventDescription
								);
			// commit TX
			$con->commit();
		}
		catch (\PropelException $ex) {
			// rollback
			$con->rollback();
			// rethrow
			throw new MomoException("ProjectManager:deleteProject() - a database error has occurred while while attempting to delete the project with ID: " . $projectId, 0, $ex);
		}									
		
		// redisplay default view
		redirect("/manageprojects");
	}
	
	
	/**
	 *  Displays the "create new project" form
	 */
	public function displayNewProjectForm() {
		
		// authorize call
		$this->authorize(Permissions::PROJECTS_CREATE_PROJECT);
		
		// get ref to managers
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
			
		// retrieve all active teams
		$activeTeams = $teamManager->getActiveTeams();
		
		// retrieve all enabled users
		$activeUsers = $userManager->getAllEnabledUsers();
		
		// prepare view data
		$data["component"] = "components/component_form_project.php";
		$data["component_title"] = "New Project";
		$data["component_mode"] = "new";
	
		$data["component_teams_active"] = $activeTeams;
		$data["component_users_active"] = $activeUsers;
		
		// render view
		$this->renderView($data);
	}
	
	
	/**
	 *  Displays the "edit project" form
	 *  
	 *  @param integer	$projectId		- the project for which to display the edit form
	 */
	public function displayEditProjectForm($projectId) {
		
		// authorize call
		$this->authorize(Permissions::PROJECTS_EDIT_PROJECT);
		
		// get ref to managers
		$projectManager = $this->getCtx()->getProjectManager();
		$teamManager = $this->getCtx()->getTeamManager();
		$userManager = $this->getCtx()->getUserManager();
			
		// query for edit target
		$editTarget = $projectManager->getProjectById($projectId);
		
		// retrieve all active teams
		$activeTeams = $teamManager->getActiveTeams();
		
		// retrieve all enabled users
		$activeUsers = $userManager->getAllEnabledUsers();
		
		// prepare view data
		$data["component"] = "components/component_form_project.php";
		$data["component_title"] = "Edit Project (" . $editTarget->getName() . ")";
		$data["component_mode"] = "edit";
	
		$data["component_edit_target"] = $editTarget;
		$data["component_teams_active"] = $activeTeams;
		$data["component_users_active"] = $activeUsers;
		
		// render view
		$this->renderView($data);
	}
	
}