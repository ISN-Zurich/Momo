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

namespace momo\projectmanager;

use momo\audittrailmanager\EventDescription;
use momo\core\exceptions\MomoException;
use momo\core\managers\BaseManager;

/**
 * ProjectManager
 * 
 * Single access point for all project related business logic
 *
 * @author  Francesco Krattiger
 * @package momo.application.managers.projectmanager
 */
class ProjectManager extends BaseManager {
	
	const MANAGER_KEY = "MANAGER_PROJECT";
	
	/**
	 * Returns all active projects
	 * 
	 * Note: Returns all projects for which "archived=0"
	 * 
	 * @return	PropelCollection
	 */
	public function getAllProjects() {
		return \ProjectQuery::create()
					->filterByArchived(false)
					->orderByName()
					->find();
	}
	
	
	/**
	 * Retrieves a project by its id
	 * 
	 * @return Project (or null)
	 * 
	 * TODO throw exception, if project not found
	 */
	public function getProjectById($projectId) {
		return \ProjectQuery::create()
					->filterById($projectId)
					->findOne();
	}
	
	
	/**
	 * Retrieves projects by a list of ids
	 *	 
	 * @param 	array	$idList		the list of project ids
	 *
	 * @return	PropelCollection	a collection of project objects
	 */
	public function getProjectsByIdList($idList) {
		return \ProjectQuery::create()
					->filterById($idList)
					->orderByName()
					->find();			
	}
	
	/**
	 * Retrieves all projects accessible to a user
	 * 
	 * Note: A project is "accessible" if:
	 * 
	 * 			- it is assigned to the user's primary team
	 * 		or
	 * 			- it is assigned directly to the user
	 * 		and
	 * 			- it is "enabled"
	 * 
	 * 
	 * @param	User	$user
	 * 
	 * @return PropelCollection
	 */
	public function getProjectsAccessibleToUser($user) {
		
		// if the user has a primary team membership obtain project ids of
		// the projects assigned to that team
		$projectIdArray = array();
		if ( $user->getPrimaryTeam() !== null ) {
			
			$teamProjects = \ProjectQuery::create()
								->filterByEnabled(true)
								->useTeamProjectQuery()
									->filterByTeam($user->getPrimaryTeam())
								->endUse()
								->find();

			$projectIdArray = $teamProjects->toKeyValue("id", "id");							
		}

		// now obtain for project ids of projects assigned directly to user
		$userProjects = $user->getProjects();
		
		if ( $userProjects->count() != 0 ) {
			$projectIdArray = array_merge($projectIdArray, $userProjects->toKeyValue("id", "id"));
		}
				
		// requery based on the obtained id list to remove dupes
		$projects = $this->getProjectsByIdList($projectIdArray);
		
		return $projects;
		
	}
	
	
	/**
	 *  Creates a new project 
	 * 
	 *  @param string				$projectName		the name of the project
	 *  @param PropelCollection		$assignedTeams		the teams assigned to the project
	 *  @param PropelCollection		$assignedUsers		the users assigned to the project
	 *  @param boolean				$enabled	
	 *  
	 *  @return Project
	 *  		
	 */
	public function createProject($projectName, $assignedTeams, $assignedUsers, $enabled) {
		
		try {
			// instantiate new Project
			$newProject = new \Project();
			
			// populate with passed arguments 
			$newProject->setName($projectName);
			$newProject->setTeams($assignedTeams);
			$newProject->setUsers($assignedUsers);
			$newProject->setEnabled($enabled);
			
			// persist
			$newProject->save();
			
			// log at level "info"
			log_message('info', "ProjectManager:createProject() - created project: " . $projectName);
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("ProjectManager:createProject() - a database error has occurred while while attempting to create the project: " . $projectName, 0, $ex);
		}
		
		return $newProject;
	}
	
	
	/**
	 *  Updates the indicated project 
	 * 
	 *  @param Project				$project			the project to update
	 *  @param string				$projectName		the name of the project
	 *  @param PropelCollection		$assignedTeams		the teams assigned to the project
	 *  @param PropelCollection		$assignedUsers		the users assigned to the project
	 *  @param boolean				$enabled
	 *  
	 *  @return Project	
	 */
	public function updateProject($project, $projectName, $assignedTeams, $assignedUsers, $enabled) {

		try {
			// update the project with passed arguments
			$project->setName($projectName);
			$project->setTeams($assignedTeams);
			$project->setUsers($assignedUsers);
			$project->setEnabled($enabled);
			
			// persist
			$project->save();
	
			// log at level "info"
			log_message('info', "ProjectManager:updateProject() - updated project with id: " . $project->getId());
		}
		catch (\PropelException $ex) {
			// rethrow
			throw new MomoException("ProjectManager:updateProject() - a database error has occurred while while attempting to update the project with ID: " . $projectId, 0, $ex);
		}
		
		return $project;
	}
	
	
	/**
	 *  Archives the indicated project
	 *  
	 *  A project is archived by:
	 *  	- removing all user references
	 *  	- removing all team references
	 *  	- setting the team's archive bit to "true"
	 *  
	 *  @param Project	$project	the project to archive 
	 *  
	 *  @return Project
	 */
	public function archiveProject($project) {
		
		try {
			// query for an empty collection
			$emptyColl = \ProjectQuery::create()
							->filterById(-1)
							->find();
							
			// assign emtpy collection to project's assigned users and teams
			$project->setUsers($emptyColl);
			$project->setTeams($emptyColl);
			
			// set to disabled
			$project->setEnabled(false);
			
			// set archive bit
			$project->setArchived(true);
			
			// persist
			$project->save();
			
			// log at level "info"
			log_message('info', "ProjectManager:archiveProject() - archived project with id: " . $project->getId());
			
		}
		catch (\PropelException $ex) {
			// rethrow		
			throw new MomoException("ProjectManager:archiveProject() - a database error has occurred while attempting to archive project with ID: " . $project->getId(), 0, $ex);
		}		
		
		return $project;
	}
	
}