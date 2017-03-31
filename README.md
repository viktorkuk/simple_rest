# Spotware PHP Talent Test

## Instructions

We expect you to be familiar with [git][1], [Vagrant][2] and [VirtualBox][3].

The question is provided to you on this zip archive.
The provisioning facility of vagrant will give you a box with the necessary software to get you started.

Please make sure you update the provisioner in case your solution requires extra packages initialization or service startup.

Your response will be pre-evaluated by an automated system by doing:

* `vagrant up --provision`
* run http://spotware-talent.local

Ensure those steps work as you would expect before asking for your answer to be evaluated.

## Goal 1

Go to http://www2.informatik.uni-freiburg.de/~cziegler/BX/ and download book ratings database.

Create REST API for accessing this database using PHP.
Read method should be accessible by anyone while other methods should be executed only by authorized person.

## Goal 2

Provide simple UI for listing this data. Design and features of this page are totally up to you!
UI must be located on a different domain.

## Bonus 1

In addition to objects CRUD methods create possibility to get books ranking per country publicly.

## Bonus 2

Create simple unit or/and functional or/and acceptance tests.

## Rules

* Create local git repo for the project.
* Commit after every step when the system is in working condition.
* Commenting is not necessary.
* Upload application (along with installation and execution instructions) to GitHub or Bitbucket.

  [1]: http://git-scm.com/
  [2]: https://www.vagrantup.com/
  [3]: https://www.virtualbox.org/
