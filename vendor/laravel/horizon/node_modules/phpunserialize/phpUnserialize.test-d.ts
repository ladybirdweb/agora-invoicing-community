import {expectType, expectError} from 'tsd';
import {phpUnserialize, unserialized} from './phpUnserialize';

expectType<unserialized>(phpUnserialize('s:3:"foo";'));
expectType<unserialized>(phpUnserialize('i:1337;'));
expectType<unserialized>(phpUnserialize('d:13.37;'));
expectType<unserialized>(phpUnserialize('b:1;'));
expectType<unserialized>(phpUnserialize('N;'));
expectType<unserialized>(phpUnserialize('a:2:{i:0;s:5:"hello";i:1;s:5:"world";}'));
expectType<unserialized>(phpUnserialize('a:2:{s:5:"hello";i:0;s:5:"world";i:1;}'));
expectType<unserialized>(phpUnserialize('O:5:"blank":0:{}'));
