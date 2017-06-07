#git避免重复输入username，password

ssh-keygen -t rsa -C "github username"
git add "id_rsa file full fath"
add the id_rsa.pub content to the github
git remote -v (if current is https ,do next)
git remote set-url origin git@github.com:"github username pre"/"project name".git
