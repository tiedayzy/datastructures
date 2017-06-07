# git避免重复输入username，password

1. ssh-keygen -t rsa -C "github username"
1. git add "id_rsa file full fath"
1. add the id_rsa.pub content to the github
1. git remote -v **(if current is https ,do next)**
1. git remote set-url origin git@github.com:"github username pre"/"project name".git
