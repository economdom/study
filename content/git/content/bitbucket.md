# BitBucket

Для того чтобы иметь полный контроль над репозиторием его нужно форкнуть в свой аккаунт.

Часть проектов размещены в репозиториях  Mercurial, а часть в Git, поэтому из этого у вас не всегда будет возможность клонировать репозиторий через Git Bash.

## Настройка Git

[Источник][1]

[1]:https://confluence.atlassian.com/bitbucket/set-up-ssh-for-git-728138079.html

* Нужно сгенерировать SSH ключ

```bash
ssh-keygen
```

В папке *~/.ssh/* должны находится файлы *id_rsa* и *id_rsa.pub*

* Создать и вставить путь к приватному ключу. Вторая строка должна иметь один отступ в один пробел. После сохранения изменений нужно перезагрузить Git Bash.

```bash
Host bitbucket.org
 IdentityFile ~/.ssh/id_rsa
```

* Создать/обновить *~/.bashrc*. После сохранения изменений нужно перезагрузить Git Bash.

```bash
SSH_ENV=$HOME/.ssh/environment
  
# start the ssh-agent
function start_agent {
    echo "Initializing new SSH agent..."
    # spawn ssh-agent
    /usr/bin/ssh-agent | sed 's/^echo/#echo/' > "${SSH_ENV}"
    echo succeeded
    chmod 600 "${SSH_ENV}"
    . "${SSH_ENV}" > /dev/null
    /usr/bin/ssh-add
}
  
if [ -f "${SSH_ENV}" ]; then
     . "${SSH_ENV}" > /dev/null
   ps -ef | grep ${SSH_AGENT_PID} | grep ssh-agent$ > /dev/null || {
      start_agent;
  }
else
    start_agent;
fi
```

* Убедитесь что идентификация вашей личности прошла успешно вводом следующей команды

```bash
ssh-add -l
```

* Перейдите в настройки *BitBucket setting / SSH keys*, создайте новый ключ и вставьте содержимое публичного SSH ключа *~/.ssh/id_rsa.pub*.

* Возвратитесь в Git Bash и введите следующую команду

```bash
ssh -T git@bitbucket.org
```

Эта команда сообщит вам какой BitBucket аккаунт может использовать данный ключ.

* Убедитесь что этой командой будет возвращенно ваше имя аккаунта. Эта команда протестирует подключение к BitBucket в качестве Git пользователя. Затем эта команда проверит соответствие приватного и публичного ключа существующего BitBucket аккаунта.

* Проверьте работу клонировал репозиторий, использую один из ниже перечисленных форматов

```bash
git clone git@bitbucket.org :< accountname>/<reponame>.git
ssh://git@bitbucket.org /< accountname>/<reponame>.git 
```

* Проверьте настройки вашего текущего репозитория

```bash
cd ~/<path_to_repo>
cat .git/config
[core]
    repositoryformatversion = 0
    filemode = true
    bare = false
    logallrefupdates = true
    ignorecase = true
    precomposeunicode = true
[remote "origin"]
    fetch = +refs/heads/*:refs/remotes/origin/*
    url = https://emmap1@bitbucket.org/emmap1/bitbucketspacestation.git
[branch "master"]
    remote = origin
    merge = refs/heads/master
```

Если вы видите `url` значит используется протокол HTTP. Вам его нужно изменить на следующий вид

```bash
[remote "origin"]
    fetch = +refs/heads/*:refs/remotes/origin/*
    url = git@bitbucket.org:emmap1/bitbucketspacestation.git
```

* Сделайте изменения и отправьте их на удалённый репозиторий

```bash
git pull
vim README.md
git add README 
git commit -m "making a change under the SSH protocol"
git push 
```

## Создание удалённого репозитория

При создании нового репозитория вам сразу же будут предложенны команды для двух случаев:

* При создании нового проекта
* Если у вас уже есть существующий проект с локальным репозиторием

## Командная работа над проектом

Это становится возможным, если вы создаёте новую команду, куда добавляете учасников и создаёте общие репозитории.

Настройка проекта ничем не отличается, просто теперь кроме вас смогут подключатся к удалённому проекту и другие учасники, получать последние изменения и отправлять свои коммиты.