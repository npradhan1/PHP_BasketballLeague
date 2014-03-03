project[fname,lname](select[dno=5](employee));
project[fname,lname]( select[dno=5](employee) join (select[pname='ProductX'](projects) join select[hours>10](works_on)));